import React from "react";
import { MdOutlineShoppingCart } from "react-icons/md";
import { FaTruck } from "react-icons/fa";
import { BiSolidUser } from "react-icons/bi";
import { initFlowbite } from "flowbite";
import logo from "../assets/img/Logo.png";
import { Link } from "react-router-dom";

function Navbar() {
  React.useEffect(() => {
    initFlowbite();
  }, []);
  return (
    <nav className="bg-white fixed w-full z-20 top-0 left-0 border-b border-gray-200">
      <div className="max-w-screen-xl flex items-center justify-between mx-auto p-4">
        <Link to="/" className="flex items-center">
          <img src={logo} className="h-12 mr-3" alt="Purenitur Logo" />
        </Link>
        <div className="flex md:order-2">
          <Link
            to="/login"
            className="inline-flex items-center px-2"
            aria-current="page"
          >
            <MdOutlineShoppingCart size="20px" />
          </Link>
          <Link
            to="/login"
            className="inline-flex items-center px-2"
            aria-current="page"
          >
            <FaTruck size="20px" />
          </Link>
          <Link
            to="/login"
            className="inline-flex items-center px-2"
            aria-current="page"
          >
            <BiSolidUser size="20px" />
          </Link>
          <button
            data-collapse-toggle="navbar-sticky"
            type="button"
            className="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
            aria-controls="navbar-sticky"
            aria-expanded="false"
          >
            <span className="sr-only">Open main menu</span>
            <svg
              className="w-5 h-5"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 17 14"
            >
              <path
                stroke="currentColor"
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                d="M1 1h15M1 7h15M1 13h15"
              />
            </svg>
          </button>
        </div>
        <div
          className="items-center justify-between hidden w-full md:flex md:w-auto md:order-1"
          id="navbar-sticky"
        >
          <ul className="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white">
            <li>
              <Link
                to="/catalog/kursi"
                className="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-teal-500 md:p-0"
              >
                Kursi
              </Link>
            </li>
            <li>
              <Link
                to="/catalog/meja"
                className="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-teal-500 md:p-0"
              >
                Meja
              </Link>
            </li>
            <li>
              <Link
                to="/catalog/lampu"
                className="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-teal-500 md:p-0"
              >
                Lampu
              </Link>
            </li>
            <li>
              <Link
                to="/catalog/tanaman"
                className="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-teal-500 md:p-0"
              >
                Tanaman
              </Link>
            </li>
            <li>
              <Link
                to="/catalog/rak"
                className="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-teal-500 md:p-0"
              >
                Rak
              </Link>
            </li>
            <li>
              <Link
                to="/catalog"
                className="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-teal-500 md:p-0"
              >
                Catalog
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  );
}

export default Navbar;
