<?php

use EvolutionCMS\Models\SiteContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScraperTasks extends Migration
{
    public function up()
    {
        Schema::create('scraper_tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamp('timestamp');
            $table->integer('status');
            $table->string('description')->nullable();
            $table->string('script');
            $table->string('url');
            $table->boolean('site_content_published');
            $table->integer('site_content_template');
            $table->integer('site_content_parent_id');
            $table->unsignedInteger('site_content_id')->nullable();
            $table->foreign('site_content_id')->references('id')->on('site_content')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scraper_tasks');
    }
}
