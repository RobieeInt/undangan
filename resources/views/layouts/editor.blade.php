<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editor Undangan — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScriptConfig
    <style>
      html, body { height: 100%; overflow: hidden; }
      .editor-layout { height: 100vh; display: flex; flex-direction: column; }
      .editor-body { flex: 1; display: flex; min-height: 0; }
    </style>
</head>
<body class="bg-gray-50 font-sans">

<div class="editor-layout">
    {{-- Slot: component renders its own topbar + body --}}
    {{ $slot }}
</div>

@stack('scripts')
</body>
</html>
