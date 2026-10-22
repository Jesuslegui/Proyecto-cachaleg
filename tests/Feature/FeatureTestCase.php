<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

abstract class FeatureTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('user');
            $table->boolean('two_factor_enabled')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('two_factor_login_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('token_hash', 64)->unique();
            $table->string('code_hash')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'expires_at']);
            $table->unique('user_id');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('size');
            $table->string('image')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('stock');
            $table->string('colors')->nullable();
            $table->string('shape')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->string('type');
            $table->string('reason')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country_code')->default('+57');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('product_description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->dateTime('estimated_delivery_at')->nullable();
            $table->string('status')->default('Recibido');
            $table->decimal('price', 8, 2)->nullable();
            $table->text('observations')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
