<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\MenuLink;    

class ReplaceMenuLinksUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $links = MenuLink::where('url','!=','')->get()->each(function ($link) {
            $link->url = str_replace('/shop', '', $link->url);
            $link->save();
        });
        
        $contructor = MenuLink::find(3);
        $contructor->url = '/custom';
        $contructor->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       $links = MenuLink::where('url','!=','')->get()->each(function ($link) {
            $link->url = '/shop'. $link->url;
            $link->save();
        });
        
        $contructor = MenuLink::find(3);
        $contructor->url = '/';
        $contructor->save();
    }
}
