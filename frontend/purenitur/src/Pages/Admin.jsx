import React, { useState, useEffect } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
import Navbar from "../Components/Navbar";
import Footer from "../Components/Footer";

function Admin() {
  const token = localStorage.getItem("token");
  const [userData, setUserData] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    // Fetch user profile using token from localStorage
    if (!token) {
      navigate("/login");
    } else {
      axios
        .get("http://127.0.0.1:8000/api/profile", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        })
        .then((response) => {
          setUserData(response.data.users);
        })
        .catch((error) => {
          console.error("Error fetching user profile:", error);
          // Handle error fetching user profile here
        });
    }
  }, [token]);

  console.log(userData);

  useEffect(() => {
    if (userData && userData.is_admin === 0) {
      navigate("/");
    }
  }, [userData, navigate]);

  return (
    <div className="min-h-screen">
      <Navbar />
      <div className="flex flex-col mt-28 mb-8">
        <div className="flex" aria-label="Breadcrumb">
          <ol className="inline-flex items-center pl-20 space-x-1 md:space-x-3 mb-4">
            <li className="inline-flex items-center">
              <Link
                to="/"
                className="inline-flex items-center text-sm font-normal text-gray-700"
              >
                <svg
                  aria-hidden="true"
                  className="w-4 h-4 mr-2"
                  fill="#7D7D7D"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span className="text-sm font-medium text-gray-500">Home</span>
              </Link>
            </li>
            <li>
              <div className="flex items-center">
                <Link
                  to="#"
                  className="inline-flex items-center text-sm font-normal text-gray-700"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="24"
                    viewBox="0 0 24 24"
                  >
                    <path
                      fill="currentColor"
                      d="m7 21l7.9-18H17L9.1 21H7Z"
                    ></path>
                  </svg>
                  <span className="ml-1 text-sm font-medium text-gray-500 md:ml-2">
                    Admin
                  </span>
                </Link>
              </div>
            </li>
          </ol>
        </div>
      </div>
      <Footer />
    </div>
  );
}

export default Admin;
