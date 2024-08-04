<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('dev_mysql')->create('settings', function (Blueprint $table) {
            $table->unsignedBigInteger('upload_slice_size')->comment('切片大小 单位kb');
            $table->bigInteger('upload_max_size')->nullable()->comment('文件上传最大大小');
            $table->text('upload_file_type')->nullable()->comment('文件类型 [\'type\' => \'image\' , \'name\' => \'图片\'] 序列化');
            $table->unsignedTinyInteger('upload_check_file_type')->comment('是否检查文件上传类型');
            $table->unsignedTinyInteger('oss_start')->default(0)->comment('是否开启oss校验');
            $table->string('oss_access_key_id', 128)->default('')->comment('oss_access_key_id');
            $table->string('oss_access_key_secret', 128)->default('')->comment('oss_access_key_secret');
            $table->string('oss_endpoint')->default('')->comment('oss endpoint  https://oss-cn-hangzhou.aliyuncs.com');
            $table->string('oss_bucket')->default('')->comment('oss bucket');
            $table->string('oss_role_arr')->default('')->comment('oss roleArn');
            $table->string('prefix_url')->default('')->comment('图片地址前缀');
            $table->unsignedBigInteger('library_max_capacity')->default(0)->comment('素材库最大容量 单位KB');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('dev_mysql')->dropIfExists('settings');
    }
};
