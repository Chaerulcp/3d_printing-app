<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ asset('contactpage/css/style.css') }}"> 
<link rel="stylesheet" href="{{ asset('contactpage/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('contactpage/css/magnific-popup.css') }}">
<link rel="stylesheet" href="{{ asset('contactpage/css/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('contactpage/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('contactpage/css/owl.theme.default.min.css') }}">
<link rel="stylesheet" href="{{ asset('contactpage/css/aos.css') }}">


<x-app-layout>
</x-app-layout>
@yield('content') 

<style>
    .custom-contact-section {
    margin-top: -150px; /* Adjust the value as needed */
}

/* Ensure the navbar is not affected */
.navbar {
    z-index: 1000; /* Ensures the navbar stays on top if necessary */
    position: relative; /* Or position: fixed; if it's a fixed navbar */
}

</style>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

    <script src="{{ asset('contactpage/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('contactpage/js/main.js') }}"></script>
