<?php

namespace App\Http\Controllers;

use App\Actions\Contact\Data\SubmitContactMessageData;
use App\Actions\Contact\SubmitContactMessage;
use App\Domain\Enums\ContactIntent;
use App\Domain\Enums\Locale;
use App\Models\Centre\Centre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class StorePublicContactMessageController extends Controller
{
    public function __invoke(Request $request, SubmitContactMessage $submitContactMessage): JsonResponse|RedirectResponse
    {
        if ($request->string('website')->toString() !== '') {
            return $this->received($request);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:1000'],
            'centre' => ['required', 'string', 'max:64'],
            'website' => ['nullable', 'string', 'max:255'],
        ], $this->validationMessages());

        $centreId = null;

        if (filled($validated['centre'] ?? null)) {
            $centreId = Centre::query()->where('code', $validated['centre'])->value('id');
        }

        try {
            $submitContactMessage(new SubmitContactMessageData(
                intent: ContactIntent::Assistance,
                name: $validated['name'],
                phone: $validated['phone'],
                email: $validated['email'],
                subject: $validated['subject'],
                message: $validated['message'],
                locale: Locale::fromString(app()->getLocale()),
                centreId: $centreId === null ? null : (int) $centreId,
                honeypot: '',
                rateLimitKey: $request->ip(),
            ));
        } catch (TooManyRequestsHttpException) {
            return $this->failed($request, ['form' => __('public.security.too_many')]);
        } catch (ValidationException $exception) {
            return $this->failed($request, $this->localizedErrors($exception->errors()));
        } catch (InvalidArgumentException) {
            return $this->failed($request, ['phone' => __('public.contact_page.message.errors.phone')]);
        }

        return $this->received($request);
    }

    /**
     * @return array<string, string>
     */
    private function validationMessages(): array
    {
        return [
            'name.required' => __('public.contact_page.message.errors.name'),
            'phone.required' => __('public.contact_page.message.errors.phone'),
            'email.required' => __('public.contact_page.message.errors.email'),
            'email.email' => __('public.contact_page.message.errors.email'),
            'centre.required' => __('public.contact_page.message.errors.centre'),
            'subject.required' => __('public.contact_page.message.errors.subject'),
            'message.required' => __('public.contact_page.message.errors.message'),
        ];
    }

    /**
     * @param  array<string, array<int, string>>  $errors
     * @return array<string, string>
     */
    private function localizedErrors(array $errors): array
    {
        $localized = [];

        foreach ($errors as $field => $messages) {
            $localized[$field] = match ($field) {
                'email' => __('public.contact_page.message.errors.email'),
                'phone' => __('public.contact_page.message.errors.phone'),
                'centreId' => __('public.contact_page.message.errors.centre'),
                default => $messages[0] ?? __('public.contact_page.message.errors.form'),
            };
        }

        return $localized;
    }

    private function received(Request $request): JsonResponse|RedirectResponse
    {
        $message = __('public.security.contact_received');

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return back()->with('contact_received', $message);
    }

    /**
     * @param  array<string, string>  $errors
     */
    private function failed(Request $request, array $errors): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => reset($errors) ?: __('public.contact_page.message.errors.form'),
                'errors' => collect($errors)->map(fn (string $message): array => [$message])->all(),
            ], 422);
        }

        return back()->withErrors($errors)->withInput();
    }
}
