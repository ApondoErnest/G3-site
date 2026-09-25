<?php

namespace App\Http\Controllers;

use App\Actions\Seo\ResolvePublicSitemap;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(ResolvePublicSitemap $resolvePublicSitemap): Response
    {
        return response()
            ->view('seo.sitemap', [
                'entries' => $resolvePublicSitemap(),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
