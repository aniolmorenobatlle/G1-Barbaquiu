<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

use App\Models\Barbecue;
use App\Models\Image;
use App\Models\BasketProduct;
use App\Models\Basket;
use App\Models\Product;
use App\Models\BarbecueFriendship;
use App\Models\Review;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user_joan = User::create([
            'name' => 'Joan',
            'surnames' => 'Paneque Domingo',
            'email' => 'jpaneque@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456')
        ]);

        $user_roman = User::create([
            'name' => 'Roman',
            'surnames' => 'Mysyura',
            'email' => 'rmysyura@cendrassos.net',
            'description' => 'Sóc un noi de 53 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/roman.jpg'
        ]);

        $user_emma = User::create([
            'name' => 'Emma',
            'surnames' => 'Cardosa',
            'email' => 'ecardosa@cendrassos.net',
            'description' => 'Sóc una noia de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/emma.jpg'
        ]);

        $user_aniol = User::create([
            'name' => 'Aniol',
            'surnames' => 'Moreno',
            'email' => 'amoreno@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/aniol.jpg'
        ]);

        $user_marcos = User::create([
            'name' => 'Marcos',
            'surnames' => 'Muñoz',
            'email' => 'mmunoz@cendrassos.net',
            'description' => 'Sóc un noi de 24 anys y soc un estudiant en el 2 DAW',
            'password' => bcrypt('123456'),
        ]);

        $user_jordi = User::create([
            'name' => 'Jordi ',
            'surnames' => 'Rodríguez',
            'email' => 'jrodriquez@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/jrodriquez.jpg'
        ]);

        $user_marcelo = User::create([
            'name' => 'Marcelo',
            'surnames' => 'Hernàndez',
            'email' => 'mhernandez@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/mhernandez.jpg'
        ]);

        $user_moya = User::create([
            'name' => 'Adrià',
            'surnames' => 'Moya',
            'email' => 'amoya@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/amoya.jpg'
        ]);

        $user_alejandro = User::create([
            'name' => 'Alejandro',
            'surnames' => 'Espinoza',
            'email' => 'aespinoza@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/aespinoza.jpg'
        ]);

        $user_ponju = User::create([
            'name' => 'Adrià',
            'surnames' => 'Poncelas',
            'email' => 'aponcelas@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/aponcelas.jpg'
        ]);

        $user_jofre = User::create([
            'name' => 'Adrià',
            'surnames' => 'Jofre',
            'email' => 'ajofre@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/ajofre.jpg'
        ]);

        $user_xvallejo = User::create([
            'name' => 'Xavier',
            'surnames' => 'Vallejo',
            'email' => 'xvallejo@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/xvallejo.jpg'
        ]);

        $user_aescribano = User::create([
            'name' => 'Alex',
            'surnames' => 'Escribano',
            'email' => 'aescribano@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/aescribano.jpg'
        ]);

        $user_stamov = User::create([
            'name' => 'Anton',
            'surnames' => 'Stamov',
            'email' => 'astamov@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/astamov.jpg'
        ]);

        $user_agarcia = User::create([
            'name' => 'Adrià',
            'surnames' => 'Garcia',
            'email' => 'agarcia@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/agarcia.jpg'
        ]);

        $user_albertito = User::create([
            'name' => 'Albert',
            'surnames' => 'Rocas',
            'email' => 'arocas@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/arocas.jpg'
        ]);

        $user_amine = User::create([
            'name' => 'Amine',
            'surnames' => 'Ryouch',
            'email' => 'aryouch@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/aryouch.jpg'
        ]);

        $user_luis = User::create([
            'name' => 'Luís',
            'surnames' => 'Arautz',
            'email' => 'larautz@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/larautz.jpg'
        ]);

        $user_llado = User::create([
            'name' => 'Silvia',
            'surnames' => 'Lladó',
            'email' => 'sllado@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/sllado.jpg'
        ]);

        $user_jespinoza = User::create([
            'name' => 'Jhosep',
            'surnames' => 'Espinoza',
            'email' => 'jespinoza@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/jespinoza.jpg'
        ]);

        $user_victor = User::create([
            'name' => 'Victoria',
            'surnames' => 'Secret',
            'email' => 'vgonzalez@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/vgonzalez.jpg'
        ]);

        $user_roger = User::create([
            'name' => 'Roger',
            'surnames' => 'Rico',
            'email' => 'rrico@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/rrico.jpg'
        ]);

        $user_pau = User::create([
            'name' => 'Pau',
            'surnames' => 'Bosch',
            'email' => 'pbosch@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/pbosch.jpg'
        ]);

        $user_patrick = User::create([
            'name' => 'Patrick',
            'surnames' => 'Serrats',
            'email' => 'pserrats@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/pserrats.jpg'
        ]);

        $user_prados = User::create([
            'name' => 'Dani',
            'surnames' => 'Prados',
            'email' => 'dprados@cendrassos.net',
            'description' => 'Sóc un noi de 22 anys que m\'agrada molt la cuina i fer barbacoes amb els amics. ',
            'password' => bcrypt('123456'),
            'image' => '/assets/img/dprados.jpg'
        ]);



        Product::create([
            'user_id' => 2,
            'name' => 'Coca-Cola',
            'price' => 2,
            'is_deleted' => false
        ]);

        Product::create([
            'user_id' => 2,
            'name' => 'Barbarechos',
            'price' => 5,
            'is_deleted' => false
        ]);

        Product::create([
            'user_id' => 1,
            'name' => 'Fanta',
            'price' => 1,
            'is_deleted' => false
        ]);

        Product::create([
            'user_id' => 3,
            'name' => 'Pan',
            'price' => 3,
            'is_deleted' => false
        ]);

        $user_roman->sendFriendRequest($user_emma);
        $user_joan->sendFriendRequest($user_emma);

        $user_joan->sendFriendRequest($user_roman);
        $user_roman->acceptFriendRequest($user_joan);

        $user_joan->sendFriendRequest($user_emma);
        $user_emma->acceptFriendRequest($user_joan);

        $user_joan->sendFriendRequest($user_aniol);
        $user_aniol->acceptFriendRequest($user_joan);

        $user_joan->sendFriendRequest($user_marcos);
        $user_marcos->acceptFriendRequest($user_joan);

        
        $user_roman->sendFriendRequest($user_emma);
        $user_emma->acceptFriendRequest($user_roman);

        $user_roman->sendFriendRequest($user_aniol);
        $user_aniol->acceptFriendRequest($user_roman);

        $user_roman->sendFriendRequest($user_marcos);
        $user_marcos->acceptFriendRequest($user_roman);

        $user_emma->sendFriendRequest($user_aniol);
        $user_aniol->acceptFriendRequest($user_emma);

        $user_emma->sendFriendRequest($user_marcos);
        $user_marcos->acceptFriendRequest($user_emma);

        
        $user_aniol->sendFriendRequest($user_marcos);
        $user_marcos->acceptFriendRequest($user_aniol);

        $user_emma->sendFriendRequest($user_joan);
        $user_roman->sendFriendRequest($user_joan);
        $user_aniol->sendFriendRequest($user_joan);
        $user_marcos->sendFriendRequest($user_joan);

        

        Barbecue::create([
            'user_id' => $user_joan->id,
            'latitude' => 41.385063,
            'longitude' => 2.173404,
            'address' => 'Barcelona',
            'title' => 'Barbacoa a Barcelona',
            'content' => '<h1>Barbacoa a Barcelona</h1><p>Benvinguts a la barbacoa a Barcelona, en aquest lloc podreu gaudir de la millor barbacoa de la ciutat. Us esperem a tots!</p>',
            'date' => '15/07/2026 18:00h'
        ]);
        Barbecue::create([
            'user_id' => $user_roman->id,
            'latitude' => 42.2666,
            'longitude' => 2.9591,
            'address' => 'Figueres, Girona',
            'title' => 'Barbacoa a Figueres, Girona',
            'content' => '<h1>Barbacoa a Figueres, Girona</h1><p>Benvinguts a la barbacoa a Figueres, en aquest lloc podreu gaudir de la millor barbacoa de la ciutat. Us esperem a tots!</p>',
            'date' => '10/03/2025 13:30h'
        ]);
        Barbecue::create([
            'user_id' => $user_emma->id,
            'latitude' => 42.2632,
            'longitude' => 3.1600,
            'address' => 'Roses, Girona',
            'title' => 'Barbacoa a Roses, Girona',
            'content' => '<h1>Barbacoa a Roses, Girona</h1><p>Benvinguts a la barbacoa a Roses, en aquest lloc podreu gaudir de la millor barbacoa de la ciutat. Us esperem a tots!</p>',
            'date' => '20/06/2024 14:00h'
        ]);
        Barbecue::create([
            'user_id' => $user_aniol->id,
            'latitude' => 42.0451,
            'longitude' => 3.1856,
            'address' => 'L\'Estartit, Girona',
            'title' => 'Barbacoa a l\'Estartit, Girona',
            'content' => '<h1>Barbacoa a l\'Estartit, Girona</h1><p>Benvinguts a la barbacoa a l\'Estartit, en aquest lloc podreu gaudir de la millor barbacoa de la ciutat. Us esperem a tots!</p>',
            'date' => '05/08/2024 21:00h'
        ]);
        Barbecue::create([
            'user_id' => $user_jordi->id,
            'latitude' => 42.248620817980914,
            'longitude' => 2.9595788863947616,
            'address' => 'Les Forques',
            'title' => 'Barbacoa a Les Forques',
            'content' => '<h1>Barbacoa a Les Forques</h1><p>Benvinguts a la barbacoa a Les Forques, en aquest lloc podreu gaudir de la millor barbacoa de la ciutat. Us esperem a tots!</p>',
            'date' => '12/09/2026 17:00h'
        ]);
        Barbecue::create([
            'user_id' => $user_moya->id,
            'latitude' => 42.157913490044486,
            'longitude' => 2.9106885668985436,
            'address' => 'Bascara, Girona',
            'title' => 'Barbacoa a Bascara, Girona',
            'content' => '<h1>Barbacoa a Bascara, Girona</h1><p>Benvinguts a la barbacoa a Bascara, en aquest lloc podreu gaudir de la millor barbacoa de la ciutat. Us esperem a tots!</p>',
            'date' => '18/07/2024 20:00h'
        ]);
        Barbecue::create([
            'user_id' => $user_jofre->id,
            'latitude' => 42.18923132512218, 
            'longitude' => 3.082013154948456,
            'address' => 'Sant Pere Pescador, Girona',
            'title' => 'Barbacoa a Sant Pere Pescador, Girona',
            'content' => '<h1>Barbacoa a Sant Pere Pescador, Girona</h1><p>Benvinguts a la barbacoa a Sant Pere Pescador, en aquest lloc podreu gaudir de la millor barbacoa de la ciutat. Us esperem a tots!</p>',
            'date' => '25/08/2024 19:30h'
        ]);
        Barbecue::create([
            'user_id' => $user_jofre->id,
            'latitude' => 42.22529712665432, 
            'longitude' => 2.8612976539571684,
            'address' => 'Navata, Girona',
            'title' => 'Barbacoa a Navata, Girona',
            'content' => '<h1>Barbacoa a Navata, Girona</h1><p>Benvinguts a la barbacoa a Navata, en aquest lloc podreu gaudir de la millor barbacoa de la ciutat. Us esperem a tots!</p>',
            'date' => '30/09/2024 18:45h'
        ]);
        
        Image::create([
            'barbecue_id' => 1,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 1,
            'path' => '/assets//img/barbaquiu2.jpg'
        ]);
        Image::create([
            'barbecue_id' => 1,
            'path' => '/assets//img/barbaquiu3.jpg'
        ]);
        Image::create([
            'barbecue_id' => 1,
            'path' => '/assets//img/barbaquiu2.jpg'
        ]);
        Image::create([
            'barbecue_id' => 1,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 1,
            'path' => '/assets//img/barbaquiu3.jpg'
        ]);
        Image::create([
            'barbecue_id' => 1,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 2,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 2,
            'path' => '/assets//img/barbaquiu2.jpg'
        ]);
        Image::create([
            'barbecue_id' => 2,
            'path' => '/assets//img/barbaquiu3.jpg'
        ]);
        Image::create([
            'barbecue_id' => 2,
            'path' => '/assets//img/barbaquiu2.jpg'
        ]);
        Image::create([
            'barbecue_id' => 2,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 2,
            'path' => '/assets//img/barbaquiu3.jpg'
        ]);
        Image::create([
            'barbecue_id' => 2,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 3,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 4,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 5,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 5,
            'path' => '/assets//img/barbaquiu2.jpg'
        ]);
        Image::create([
            'barbecue_id' => 5,
            'path' => '/assets//img/barbaquiu3.jpg'
        ]);
        Image::create([
            'barbecue_id' => 6,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 6,
            'path' => '/assets//img/barbaquiu2.jpg'
        ]);
        Image::create([
            'barbecue_id' => 6,
            'path' => '/assets//img/barbaquiu3.jpg'
        ]);
        Image::create([
            'barbecue_id' => 7,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 7,
            'path' => '/assets//img/barbaquiu2.jpg'
        ]);
        Image::create([
            'barbecue_id' => 7,
            'path' => '/assets//img/barbaquiu3.jpg'
        ]);
        Image::create([
            'barbecue_id' => 8,
            'path' => '/assets//img/barbaquiu1.jpg'
        ]);
        Image::create([
            'barbecue_id' => 8,
            'path' => '/assets//img/barbaquiu2.jpg'
        ]);
        Image::create([
            'barbecue_id' => 8,
            'path' => '/assets//img/barbaquiu3.jpg'
        ]);
        
        BarbecueFriendship::create([
            'barbecue_id' => 1,
            'user_id' => 1,
            'guest_id' => 2,
            'is_admin' => false,
            'accepted' => true
        ]);

        Basket::create([
            'id' => 1,
            'barbecue_id' => 1,
        ]);

        BasketProduct::create([
            'basket_id' => 1,
            'product_id' => 1,
            'user_id' => 2,
            'quantity' => 2,
            'price' => 2
        ]);

        BasketProduct::create([
            'basket_id' => 1,
            'product_id' => 2,
            'user_id' => 2,
            'quantity' => 3,
            'price' => 5
        ]);

        BasketProduct::create([
            'basket_id' => 1,
            'product_id' => 3,
            'user_id' => 2,
            'quantity' => 1,
            'price' => 1
        ]);

        BasketProduct::create([
            'basket_id' => 1,
            'product_id' => 4,
            'user_id' => 2,
            'quantity' => 3,
            'price' => 3
        ]);

        Comment::create([
            'user_id' => 1,
            'barbecue_id' => 1,
            'comment' => 'Hola, quin dia es la barbacoa?'
        ]);
        Comment::create([
            'user_id' => 7,
            'barbecue_id' => 1,
            'comment' => 'Te molt bona pinta!'
        ]);

        Comment::create([
            'user_id' => 8,
            'barbecue_id' => 2,
            'comment' => 'Epaa, aceptam la solicitud!'
        ]);
        Comment::create([
            'user_id' => 10,
            'barbecue_id' => 2,
            'comment' => 'Pero que guay!'
        ]);


        Comment::create([
            'user_id' => 6,
            'barbecue_id' => 5,
            'comment' => 'Esta una mica lluny no?'
        ]);

        Comment::create([
            'user_id' => 9,
            'barbecue_id' => 5,
            'comment' => 'Jo anire 100% segur!'
        ]);
        Comment::create([
            'user_id' => 11,
            'barbecue_id' => 5,
            'comment' => 'Pero que guay!'
        ]);
    }

}