<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Frame;
use App\Models\Order;
use App\Models\Photo;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'email' => 'sample@email.com',
            'full_name' => 'Sample User',
            'password' => Hash::make('admin'),
            'create_time' => '2023-07-10 00:00',
            'is_admin' => true
        ]);
        $user = User::create([
            'email' => 'user@email.com',
            'full_name' => 'User',
            'password' => Hash::make('user'),
            'create_time' => '2023-07-10 00:00',
            'is_admin' => false
        ]);

        $size = Size::create([
            'size' => '1 Inch',
            'width' => '0.2',
            'height' => '2.5',
            'price' => '3.6'
        ]);
        $frame = Frame::create([
            'url' => 'http://127.0.0.1/02_ServerSide_A/storage/app/image/2QUImqX70DNJYt99g1nm5w2oyYA1VNUsji0aUPc2.jpg',
            'price' => '1.2',
            'name' => 'Frame',
            'size' => '5 Inch'
        ]);

        $order = Order::create([
            'full_name' => 'Matthew',
            'phone_number' => '10001000',
            'shipping_address' => 'Where',
            'card_number' => '3223222222',
            'name_on_card' => 'Matthew ws02',
            'exp_date' => '2024-02-20',
            'cvv' => '246',
            'total' => '10',
            'order_placed' => '2022-02-02 00:00',
            'status' => 'Valid',
            'user_id' => $user->id,
        ]);
        Photo::create([
            'edited_url' => 'http://127.0.0.1/02_ServerSide_A/storage/app/image/2QUImqX70DNJYt99g1nm5w2oyYA1VNUsji0aUPc2.jpg',
            'original_url' => 'http://127.0.0.1/02_ServerSide_A/storage/app/image/2QUImqX70DNJYt99g1nm5w2oyYA1VNUsji0aUPc2.jpg',
            'size' => '5 Inch',
            'print_price' => '1.2',
            'frame_price' => '1.2',
            'status' => 'uploaded',
            'frame_id' => $frame->id,
            'user_id' => $user->id,
            'size_id' => $size->id,
            'order_id' => $order->id
        ]);
    }
}
