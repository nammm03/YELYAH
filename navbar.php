<?php include 'header.php' ?>
<nav class="fixed top-0 w-full z-50 bg-gradient-to-l from-stone-50 to-gray-400 text-black shadow-md h-[100px]">
  <div class="max-w-7xl mx-[100px] px-0 h-full flex justify-between items-center">
    <img src="assets/JRZ_NEXUS.png" alt="Home" class="h-[180px] w-auto object-contain" />



    <ul class="flex space-x-10 items-center relative">
      <li>
        <a href="#intro" class="text-blue-400 font-semibold">Home</a>
      </li>
      <li>
        <a href="#about" class="hover:text-blue-400 transition">About Us</a>
      </li>
      <li>
        <a href="#contact" class="hover:text-blue-400 transition">Contact Us</a>
      </li>

      <li class="relative group">
        <a href="#" class="flex items-center hover:text-blue-500">
          Web Solutions <span class="ml-1">&#9662;</span>
        </a>
        <ul class="absolute left-0 mt-0 hidden w-40 bg-white border border-gray-200 rounded shadow-md group-hover:block z-10">
          <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Web Design</a></li>
          <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">SEO</a></li>
          <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Marketing</a></li>
        </ul>
      </li>

      <li>
        <a href="#" onclick="openLoginModal()" class="hover:text-blue-400 transition">Login</a>
      </li>
    </ul>
  </div>
</nav>