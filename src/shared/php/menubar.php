<?php

class Menubar
{
    private array $links;

    public function __construct(array $links)
    {
        $this->links = $links;
    }

    public function build()
    {

        $html = '<div class="menubar-container">';
        $html = '<div class="menubar">';

        // company logo section
        $html .= '<div class="company-logo">';
        $html .= '<img class="company-logo-img" src="/img/logo.png">';
        $html .= '</div>';

        //hamburger section
        $html .=
            '<button class="hamburger" id="hamburger" aria-label="Open menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>';

        //links
        $html .= '<div class="links" id="links">';

        //iterate through each link and add it to the output
        foreach ($this->links as $link) {
            foreach ($link as $name => $url) {
                $html .= '
                    <div class="menubar-link">
                        <a href="' . htmlspecialchars($url) . '">
                            <h2>' . $name . '</h2>
                        </a>
                    </div>';
            }
        }

        $html .= '</div>';

        $loggedIn = isset($_SESSION['email']);

        $html .= '
    <div class="right-hand-menu">
    <div class="cart">
        <div class="cart-circle" id="cart-circle">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="dropdown-arrow-cart"></span>
        </div>

        <div class="menu-dropdown" id="cart-dropdown">
            <div class="spinner-container">
                <div class="spinner">
                </div>
            </div>
        </div>
    </div>
    
    ';

        $html .= '<div class="profile">';

        if (!$loggedIn) {
            $html .= '
        <a href="/login" class="profile-button" id="profile-circle">
            <div class="profile-circle">
                <i class="fa-regular fa-user"></i>
            </div>
        </a>';
        } else {
            $html .= '
        <div class="profile-button" id="profile-circle">
            <div class="profile-circle">
                <i class="fa-regular fa-user"></i>
                <span class="dropdown-arrow-profile"></span>
            </div>
        </div>

        <div class="menu-dropdown" id="profile-dropdown">
            <a href="/profile">Profile</a>
            <a href="/settings">Settings</a>
            <a href="/logout">Log out</a>
        </div>';
        }

        $html .= '
    </div>';


        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
