import { Link, Outlet } from "react-router-dom";
import {Home , LogIn } from "lucide-react";
import { ModeToggle } from "../components/mode-toggle";

export default function Layout(){


    return<>


    <header>

    <nav className="bg-white shadow-md">
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div className="flex justify-between h-16">

        <div className="flex items-center">
          <a href="#" className="text-2xl font-bold text-gray-800">Logo</a>
        </div>


        <div className="hidden md:flex items-center space-x-6">
          <Link to="/" className="text-gray-600 hover:text-gray-800"><Home /></Link>
          <Link to="/login" className="text-gray-600 hover:text-gray-800"><LogIn  /></Link>
          <ModeToggle />
        </div>



      </div>
    </div>



  </nav>
    </header>

    <main>
        <Outlet />
    </main>


    <footer></footer>
    </>
}
