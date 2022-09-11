<?php

namespace App\Providers;

use App\Models\Link;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class BlogConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      if (Schema::hasTable('config')) {
        $config = DB::table('config')->first();
        $links = Link::where('position', 'navbar')->get();
        config([
          'app.title' => $config->blog_title,
          'app.description' => $config->blog_description,
          'app.sociallinks' => collect(['facebook', 'instagram', 'youtube', 'twitter']),
          'app.facebook' => $config->facebook,
          'app.instagram' => $config->instagram,
          'app.youtube' => $config->youtube,
          'app.twitter' => $config->twitter,
          'app.comments' => $config->allow_comments,
          'app.search' => $config->allow_search,
          'app.navbar.fixed' => $config->fixed_navbar,
          'app.navbar.links' => $links,
        ]);
      }
    }
}