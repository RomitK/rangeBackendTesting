<?php

use App\Enums\PropertyStatus\PropertyStatusEnum;
use App\Enums\PropertyType\PropertyTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $propertyStatusEnumValues = array_values(PropertyStatusEnum::toValues());
            $propertyTypeEnumValues = array_values(PropertyTypeEnum::toValues());
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('mobile_country_code');
            $table->string('mobile');
            $table->enum('property_status', $propertyStatusEnumValues);
            $table->enum('property_type', $propertyTypeEnumValues);
            $table->integer('number_of_rooms')->default(0);
            $table->decimal('min_price');
            $table->decimal('max_price');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
