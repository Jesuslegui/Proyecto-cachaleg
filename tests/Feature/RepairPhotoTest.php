<?php

namespace Tests\Feature;

use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RepairPhotoTest extends FeatureTestCase
{
    public function test_repair_photo_can_be_uploaded_replaced_and_displayed(): void
    {
        Storage::fake('public');
        $user = User::create([
            'name' => 'Usuario',
            'email' => 'user@example.test',
            'password' => 'password123',
            'role' => 'user',
        ]);

        $this->actingAs($user)->post(route('repairs.store'), [
            'customer_name' => 'Cliente',
            'product_description' => 'Zapato deportivo negro',
            'image' => $this->fakePng('zapato.png'),
        ])->assertRedirect(route('repairs.index'));

        $repair = Repair::firstOrFail();
        $firstImage = $repair->image;
        Storage::disk('public')->assertExists($firstImage);

        $this->get(route('repairs.index'))->assertOk()->assertSee($firstImage);
        $this->get(route('repairs.show', $repair))->assertOk()->assertSee($firstImage);

        $this->put(route('repairs.update', $repair), [
            'customer_name' => 'Cliente',
            'product_description' => 'Zapato deportivo negro',
        ])->assertRedirect(route('repairs.index'));
        $this->assertSame($firstImage, $repair->fresh()->image);

        $this->put(route('repairs.update', $repair), [
            'customer_name' => 'Cliente',
            'product_description' => 'Zapato deportivo negro',
            'image' => $this->fakePng('nuevo-zapato.png'),
        ])->assertRedirect(route('repairs.index'));

        $newImage = $repair->fresh()->image;
        $this->assertNotSame($firstImage, $newImage);
        Storage::disk('public')->assertMissing($firstImage);
        Storage::disk('public')->assertExists($newImage);
    }

    public function test_repair_rejects_non_image_uploads(): void
    {
        Storage::fake('public');
        $user = User::create([
            'name' => 'Usuario',
            'email' => 'user@example.test',
            'password' => 'password123',
            'role' => 'user',
        ]);

        $this->actingAs($user)->post(route('repairs.store'), [
            'customer_name' => 'Cliente',
            'product_description' => 'Zapato deportivo negro',
            'image' => UploadedFile::fake()->create('no-es-imagen.txt', 1, 'text/plain'),
        ])->assertSessionHasErrors('image');

        $this->assertDatabaseCount('repairs', 0);
    }

    private function fakePng(string $name): UploadedFile
    {
        $content = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADUlEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC', true);

        return UploadedFile::fake()->createWithContent($name, $content ?: '');
    }
}