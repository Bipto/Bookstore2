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
        $html .= '<img class="company-logo-img" src="img/logo.png">';
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

        $html .= '
            <div class="profile">
                <div class="profile-button" id="profile-circle">
                    <div class="profile-circle">
                        <i class="fa-regular fa-user"></i>
                        <span class="dropdown-arrow"></span>
                    </div>

                </div>

                <div class="profile-dropdown" id="profile-dropdown">
                    <a href="profile.php">Profile</a>
                    <a href="settings.php">Settings</a>
                    <a href="logout.php">Log out</a>
                </div>
            </div>';

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
