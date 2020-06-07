<style>
    #header {
        padding: 10px 25px;
    }
    .header-container {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }

    .header-container > .logo {
        display: flex;
        align-items: center;
    }

    .header-container > .logo > img {
        margin-right: 10px;
    }

    .menu {
        display: flex;
        align-items: center;
    }

    .menu > .submenu {
        margin-left: 25px;
    }

    .menu > .submenu > a {
        text-decoration: none;
    }

    .menu > .submenu > a > span {
        color: black;
        cursor: pointer;
    }

    .menu > .submenu > a > span:hover {
        color: #f8c94e;
    }

    .menu > .submenu > a > span.active {
        padding: 6px 8px;
        background: #f8c94e;
        border-radius: 3px;
        color: white;
    }
</style>

<div id="header">
    <div class="header-container">
        <div class="logo">
            <img src="{{ asset("logo.png") }}" alt="logo" height="40px">
            E-Book Libraries
        </div>
        <div class="menu">
            @if(! \Illuminate\Support\Facades\Auth::check())
                <div class="submenu">
                    <a href="{{ route('home') }}">
                        <span class="{{ (\Request::route()->getName() == "home") ? "active" : "" }}">Home</span>
                    </a>
                </div>

                <div class="submenu">
                    <a href="{{ route('login') }}">
                        <span class="{{ (\Request::route()->getName() == "login") ? "active" : "" }}">Login</span>
                    </a>
                </div>
            @else
                <div class="submenu">
                    <a href="{{ route('home') }}">
                        <span class="{{ (\Request::route()->getName() == "home") ? "active" : "" }}">Home</span>
                    </a>
                </div>
                @if(\Illuminate\Support\Facades\Auth::user()->role == "admin")
                    <div class="submenu">
                        <a href="{{ route('manage-user') }}">
                            <span class="{{ (\Request::route()->getName() == "manage-user") ? "active" : "" }}">User</span>
                        </a>
                    </div>
                    <div class="submenu">
                        <a href="{{ route('manage-book-type') }}">
                            <span class="{{ (\Request::route()->getName() == "manage-book-type") ? "active" : "" }}">Book Type</span>
                        </a>
                    </div>
                    <div class="submenu">
                        <a href="{{ route('manage-classroom') }}">
                            <span class="{{ (\Request::route()->getName() == "manage-classroom") ? "active" : "" }}">Classroom</span>
                        </a>
                    </div>

                @elseif(\Illuminate\Support\Facades\Auth::user()->role == "user")
                    <div class="submenu">
                        <a href="{{ route('manage-my-book') }}">
                            <span class="{{ (\Request::route()->getName() == "manage-my-book") ? "active" : "" }}">My Book</span>
                        </a>
                    </div>
                @endif

                <div class="submenu">
                    <a href="{{ route('manage-profile') }}">
                        <span class="{{ (\Request::route()->getName() == "manage-profile") ? "active" : "" }}">Profile</span>
                    </a>
                </div>
                <div class="submenu">
                    <a href="{{ route('logout-action') }}">
                        <span>Logout</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

