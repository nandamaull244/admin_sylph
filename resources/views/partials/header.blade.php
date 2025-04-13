<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset ('assets') }}/images/logos/logo.svg" />
    <link rel="stylesheet" href="{{ asset ('assets') }}/css/styles.min.css" />
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    @stack('css')
  </head>