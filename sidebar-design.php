<style>
    @font-face {
        font-family: 'nautigal';
        src: url(font/TheNautigal-Regular.ttf);
    }
    *, *::before, *::after {
        box-sizing: border-box;
    }
    * {
        margin: 0;
    }
    p{
        margin: 0;
    }
    body{
        font-family: Arial, sans-serif;
    }
    #sidebar span{
        font-family: 'nautigal';
        font-size: 50px !important;
    }
    .navbar {
        margin-left: 250px; 
        z-index: 1; 
        width: calc(100% - 250px);
        height: 50px;
        transition: margin-left 0.3s ease; 
    }
    #sidebar {
        width: 250px;
        position: -webkit-sticky;
        position: sticky;
        top: 0; 
        height: 100vh;
        overflow-y: auto; 
        transition: transform 0.3s ease;
        background: #001A3E;
    }

    #main-content {
        transition: margin-left 0.3s ease;
        margin: 0 0 0 270px; 
    }

    #hamburger {
        border: none;
        background: none;
    }
    hr{
        background-color: #ffff;
        height: 1.5px;
    }
    
    #sidebar .nav-link {
        color: #fff;
        padding: 10px;
        border-radius: 4px;
        transition: background-color 0.3s, color 0.3s;
        margin-bottom: 2px;
    }

    #sidebar .nav-link:hover, #sidebar .nav-link.active {
        background-color: #fff !important;
        color: #000 !important;
    }
    @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-250px);
            }
            #sidebar.show {
                transform: translateX(0);
            }

            .navbar {
                margin-left: 0;
                width: 100%; 
            }
            .navbar.shifted {
                margin-left: 250px; 
                width: calc(100% - 250px); 
            }

            #main-content {
                margin-left: 0;
            }
            #main-content.shifted {
                margin-left: 250px; 
            }
        }
</style>