<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productNames = [
            'Smartphone X500', 'Laptop Pro 15', 'Wireless Headphones S5', 'Gaming Laptop Ultra', 'Smartwatch Max',
            'Bluetooth Speaker ZX', '4K UHD TV', 'Noise Cancelling Headphones', 'Smart Home Hub', 'Electric Toothbrush 3000',
            'Smartphone Pro Plus', 'Fitness Tracker Plus', 'Gaming Console 2023', 'Wireless Earbuds Elite', 'LED Monitor 24"',
            'Touchscreen Tablet', 'Laptop Air 13"', 'Portable Charger X2', 'Smart Thermostat', 'Smartphone Camera Kit',
            'Virtual Reality Headset', 'Portable Power Bank', 'Gaming Keyboard RGB', 'Ultra HD Projector', 'Smart Ring Health Monitor',
            'Car Dash Camera', 'LED Desk Lamp', 'Compact Soundbar', 'Smart Lock', 'Smart TV Stick',
            'Wireless Mouse Pro', 'Cordless Vacuum Cleaner'
        ];
        $productDescriptions = [
            'A high-performance smartphone with ultra-fast processing and a stunning display for all your needs.',
            'A lightweight and sleek laptop with an advanced processor for smooth multitasking and gaming.',
            'Noise-cancelling wireless headphones for an immersive sound experience during travel or work.',
            'Powerful gaming laptop with the latest graphics card and high refresh rate for smooth gameplay.',
            'A smartwatch that tracks your health and fitness, offering personalized insights and notifications.',
            'Portable Bluetooth speaker with rich bass and high-quality sound for any occasion or event.',
            'Experience true-to-life visuals with this 4K UHD TV, perfect for watching movies and sports.',
            'Noise-cancelling headphones with Bluetooth for a comfortable, wireless experience at work or play.',
            'Smart home hub to control your smart devices from one central location for convenience and efficiency.',
            'Electric toothbrush with advanced sonic technology for cleaner teeth and healthier gums.',
            'Premium smartphone with an incredible camera and long-lasting battery life for all your needs.',
            'Fitness tracker to monitor your heart rate, steps, and more, helping you stay on top of your health.',
            'Next-gen gaming console with exclusive titles and enhanced graphics for the ultimate experience.',
            'True wireless earbuds with noise isolation for superior audio quality, designed for active users.',
            '24-inch LED monitor offering vibrant colors and sharp resolution, ideal for gaming and productivity.',
            'High-performance tablet with a sleek design, perfect for both entertainment and productivity.',
            'Ultrafast 13-inch laptop with a retina display and powerful battery life for working on the go.',
            'Portable power bank to keep your devices charged, no matter where you are, with a compact design.',
            'Smart thermostat that adapts to your lifestyle and adjusts the temperature to save energy.',
            'Smartphone camera kit for professional-grade photography and video recording on your phone.',
            'Virtual reality headset offering an immersive gaming and entertainment experience.',
            'Powerful portable charger to keep your devices charged when you need it most, at home or on the go.',
            'RGB gaming keyboard designed for responsive typing and customizable lighting for a personalized touch.',
            'Ultra HD projector for stunning picture quality, perfect for home theaters or business presentations.',
            'Smart ring that tracks your health data and connects to your phone for convenient monitoring.',
            'Compact and efficient car dash camera that records high-definition footage while you drive.',
            'Sleek LED desk lamp with adjustable brightness levels, perfect for any workspace or study room.',
            'Compact soundbar offering rich, immersive sound with easy Bluetooth connectivity for any device.',
            'Smart lock that lets you control and monitor your door access from your smartphone.',
            'Smart TV Stick that turns any TV into a smart TV with access to your favorite streaming services.',
            'Ergonomic wireless mouse designed for precision, comfort, and extended use during work sessions.',
            'Cordless vacuum cleaner with powerful suction and multiple attachments for deep cleaning.'
        ];


        return [
            // Select random name from the predefined array
            'name' => $this->faker->randomElement($productNames),

            // Select a random description from the predefined list
            'description' => $this->faker->randomElement($productDescriptions),

            // Generate price between $50 and $2000
            'price' => $this->faker->randomFloat(2, 50, 2000),

            // Random stock quantity between 1 and 100
            'stock_quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
