<?php

namespace App\Http\Controllers;


class PageController extends Controller
{

    public function homepage()
    {
        $data = [];
        $data['title'] = 'Homepage | MagicAK';
        $data['homepage'] = 'homepage';

        return view('landingpage.homepage',$data);
    }
    public function services()
    {
        $data = [];
        $data['title'] = 'Services | MagicAK';

        return view('landingpage.services',$data);
    }
    public function aboutus()
    {
        $data = [];
        $data['title'] = 'About Us | MagicAK';

        return view('landingpage.aboutus',$data);
    }

    public function support()
    {
        $data = [];
        $data['title'] = 'Support | MagicAK';

        return view('landingpage.support',$data);
    }
    public function partners()
    {
        $data = [];
        $data['title'] = 'Partners | MagicAK';

        return view('landingpage.partners',$data);
    }
    public function contact()
    {
        $data = [];
        $data['title'] = 'Contact | MagicAK';

        return view('landingpage.contact',$data);
    }
}
