New contact message

Intent: {{ $intent }}
From: {{ $name }} ({{ $phone }})
Email: {{ $email }}
Subject: {{ $subject }}
@if ($centreName)
Centre: {{ $centreName }}
@endif
Locale: {{ $locale }}

Message:
{{ $body }}
