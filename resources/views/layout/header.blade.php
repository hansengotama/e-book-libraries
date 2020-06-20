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
        <div class="menu" style="position: relative">
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
                        <a href="{{ route('view-my-class') }}">
                            <span class="{{ (\Request::route()->getName() == "view-my-class") ? "active" : "" }}">My Class</span>
                        </a>
                    </div>
                @endif

                <div class="submenu">
                    <a href="{{ route('manage-my-book') }}">
                        <span class="{{ (\Request::route()->getName() == "manage-my-book") ? "active" : "" }}">My Book</span>
                    </a>
                </div>


                <div class="submenu" onclick="toggleDropdown()">
                    <a>
                        <span>{{ \Illuminate\Support\Facades\Auth::user()->name }} <i class="fa fa-angle-down"></i></span>
                    </a>
                </div>
                <div style="position: absolute; top: 40px; right: 0; background: white; padding: 10px 20px; box-shadow: 5px 5px 2px 0 rgba(0,0,0,0.10); display: none; z-index: 9999" id="menu-profile">
                    <div class="submenu" style="margin-bottom: 20px; margin-top: 10px">
                        <a href="{{ route('manage-profile') }}">
                            <span class="{{ (\Request::route()->getName() == "manage-profile") ? "active" : "" }}" style="text-decoration: none; color: black;">Profile</span>
                        </a>
                    </div>
                    <div class="submenu">
                        <a href="{{ route('logout-action') }}">
                            <span class="{{ (\Request::route()->getName() == "manage-profile") ? "active" : "" }}" style="text-decoration: none; color: black;">Logout</span>
                        </a>
                    </div>
                </div>

            @endif
        </div>
    </div>
</div>

