@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <h1 class="text-2xl font-black tracking-tight text-[#0d1b3e]">{{ $title }}</h1>
    <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>
</div>
