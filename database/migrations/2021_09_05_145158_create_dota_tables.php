<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDotaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("genders", function (Blueprint $table) {
            $table->id();
            $table->string("gender")->unique("UK_gender");
        });

        Schema::create("dota_users_personal_information", function (
            Blueprint $table
        ) {
            $table->id();
            $table->string("name", 100);
            $table->timestamp("date_of_birth")->nullable();
            $table->unsignedBigInteger("gender_id");
            $table
                ->foreign("gender_id")
                ->references("id")
                ->on("genders")
                ->onDelete("cascade");
        });

        Schema::create("attributes", function (Blueprint $table) {
            $table->id();
            $table->string("name", 100)->unique("UK_name");
        });

        Schema::create("stats", function (Blueprint $table) {
            $table->id();
            $table->float("strength");
            $table->float("lvl_strength");
            $table->float("agility");
            $table->float("lvl_agility");
            $table->float("intelligence");
            $table->float("lvl_intelligence");
        });

        Schema::create("skills", function (Blueprint $table) {
            $table->id();
            $table->string("skill_name", 100);
            $table
                ->text("img_url")
                ->comment(
                    "Should be relative or absolute url to the img on the server."
                );
            $table->text("description");
        });

        Schema::create("heroes", function (Blueprint $table) {
            $table->id();
            $table->string("name", 100);
            $table
                ->text("img_url")
                ->comment(
                    "Should be relative or absolute url to the img on the server."
                );
            $table->date("release_date");
            $table->text("description");
            $table->unsignedFloat("winrate")->default(0.0);
            $table->unsignedBigInteger("main_attribute_id");
            $table
                ->foreign("main_attribute_id")
                ->references("id")
                ->on("attributes")
                ->onDelete("cascade");
            $table->unsignedBigInteger("stats_id");
            $table
                ->foreign("stats_id")
                ->references("id")
                ->on("stats")
                ->onDelete("cascade");
        });

        // This schema probably should be controlled by laravel auth service.
        // TODO: Read about auth and, probably, change this table
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("email", 255);
            $table->string("phone", 255);
            $table->text("password");
            $table->unsignedBigInteger("favorite_hero_id");
            $table
                ->foreign("favorite_hero_id")
                ->references("id")
                ->on("heroes")
                ->onDelete("cascade");
        });

        Schema::create("articles", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("author_id");
            $table
                ->foreign("author_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            // Content will be just a simple markdown.
            // Img links and headers will be placed into text directly.
            $table->text("content");
        });

        Schema::create("comments", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("author_id");
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("article_id")->nullable();

            $table
                ->foreign("author_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table
                ->foreign("article_id")
                ->references("id")
                ->on("articles")
                ->onDelete("cascade");
        });

        // Since winrate is percentage it can't be more then 100%.
        DB::statement(
            "ALTER TABLE heroes ADD CONSTRAINT chk_max_winrate CHECK (winrate <= 100.0)"
        );
    }

    /**
     * Reverse the migrations.
     * This one could breake because I'm not sure about order.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("comments");
        Schema::dropIfExists("articles");
        Schema::dropIfExists("users");
        Schema::dropIfExists("heroes");
        Schema::dropIfExists("skills");
        Schema::dropIfExists("stats");
        Schema::dropIfExists("attributes");
        Schema::dropIfExists("dota_users_personal_information");
        Schema::dropIfExists("genders");
    }
}
