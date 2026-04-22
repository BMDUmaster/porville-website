@extends('frontend.layouts.app')
@section('title', 'About Us')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="nunito font-extrabold text-3xl text-gray-800 mb-4">About FarmSea</h1>
    <p class="text-gray-600 leading-relaxed mb-4">FarmSea brings farm-fresh chicken, premium mutton, and fresh seafood directly to your doorstep. We ensure hygienic processing, quality cuts, and same-day delivery for the freshest experience.</p>
    <p class="text-gray-600 leading-relaxed mb-4">Our mission is to connect farmers and fishermen directly with consumers, eliminating middlemen and ensuring fair prices for both producers and buyers.</p>
    <h2 class="text-xl font-bold text-gray-800 mt-8 mb-3">Our Values</h2>
    <ul class="space-y-2 text-gray-600">
        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-600"></i> 100% Natural — No hormones or chemicals</li>
        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-600"></i> Cold Chain Delivery — Fresh at every step</li>
        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-600"></i> FSSAI Certified — Quality you can trust</li>
        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-600"></i> Same Day Delivery — Order before 10 AM</li>
    </ul>
</div>
@endsection
