<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        \App\Models\Video::where('url', 'like', '%rutube%')->update(['platform' => 'rutube']);
        \App\Models\Video::where('url', 'like', '%vk.com%')->orWhere('url', 'like', '%vk.ru%')->update(['platform' => 'vk']);
        \App\Models\Video::where('url', 'like', '%yandex%')->orWhere('url', 'like', '%ya.ru%')->update(['platform' => 'yandex']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
