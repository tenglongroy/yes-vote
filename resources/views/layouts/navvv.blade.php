
<nav class="navbar navbar-default navbar-fixed-top navbar-light" style="background-color: #e3f2fd;">
    <div class="container">
        <div class="navbar-header">



            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="navbar ui-widget" style="position: absolute;left: 30%;">
            <form action="{{ url('/ajax/search') }}" method="GET">
                <input type="text" id="search_input" name="search_input">
                <input type="submit" value="search">
            </form>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <div class="row">
                        <div class="col-sm-6"><a href="{{ route('login') }}">Login</a></div>
                        <div class="col-sm-6"><a href="{{ route('register') }}">Register</a></div>
                    </div>
                @else
                    {{--<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a onclick="event.preventDefault();
                                             document.getElementById('user-profile').submit();">
                                    Profile
                                </a>
                                <form id="user-profile" action="/users/{{ Auth::user()->id }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>--}}
                <div class="w3-container">
                    <div class="w3-dropdown-hover">
                        <div class="w3-button w3-blue">{{ Auth::user()->name }}<span class="caret"></span></div>
                        <div class="w3-dropdown-content w3-bar-block w3-border">
                            <a onclick="event.preventDefault();
                            document.getElementById('user-profile').submit();"
                               class="w3-bar-item w3-button">
                                Profile</a>
                            <form id="user-profile" action="/users/{{ Auth::user()->id }}" method="GET" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();"
                               class="w3-bar-item w3-button">
                                Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </ul>
        </div>
    </div>
</nav>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script>
$( "#search_input" ).autocomplete({
    source: function( request, response ) {
        $.ajax( {
            url: "{{ url('/ajax/search') }}",
            dataType: "jsonp",
            data: {
                term: request.term
            },
            success: function( data ) {
                console.log('in success');
                response( data );
            }
        } );
    },
    autoFocus:true,
    minLength: 2,
    select: function( event, ui ) {
        console.log('in select');
        console.log(ui);
        log( "Selected: " + ui.item.value + " aka " + ui.item.id );
    }
} );
</script>




{{--
https://v4-alpha.getbootstrap.com/components/navbar/
<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">Navbar</a>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Features</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Pricing</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Dropdown link
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
    </div>
</nav>


<nav class="navbar navbar-light bg-faded">
    <form class="form-inline">
        <input class="form-control mr-sm-2" type="text" placeholder="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
</nav>--}}
