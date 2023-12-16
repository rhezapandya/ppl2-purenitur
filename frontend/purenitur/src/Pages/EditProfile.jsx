import React, { useState, useEffect } from "react";
import Footer from "../Components/Footer";
import Navbar from "../Components/Navbar";
import { Link, useNavigate } from "react-router-dom";
import axios from "axios";

function EditProfile() {
  const token = localStorage.getItem("token");
  const navigate = useNavigate();
  const [userData, setUserData] = useState({
    email: "",
    username: "",
    first_name: "",
    last_name: "",
    gender: "",
    telephone: "",
    address: "",
  });

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
  }, [token, navigate]);

  const handleChange = (event) => {
    const { id, value, type } = event.target;

    if (type === "radio" && id === "gender-option-1") {
      setUserData((prevFormData) => ({
        ...prevFormData,
        gender: "M",
      }));
    } else if (type === "radio" && id === "gender-option-2") {
      setUserData((prevFormData) => ({
        ...prevFormData,
        gender: "W",
      }));
    } else {
      setUserData((prevFormData) => ({
        ...prevFormData,
        [id]: value,
      }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post(
        "http://127.0.0.1:8000/api/profile-update",
        userData,
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
      alert("Edit profile successfully");
    } catch (error) {
      console.error("Edit profile failed:", error);
    }
  };

  const handleLogout = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post(
        "http://127.0.0.1:8000/api/logout",
        null, // pass null as the second argument for the request body since it's a logout request
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
      // Handle successful logout
      console.log("User logged out successfully", response);
      // Clear localStorage or perform any other necessary actions
      localStorage.removeItem("token");
      navigate("/login");
    } catch (error) {
      console.error("Logout failed:", error);
      // Handle logout failure here
    }
  };

  const handleChangePassword = async (e) => {
    e.preventDefault();

    const newPassword = document.getElementById("password").value;
    const confirmPassword = document.getElementById(
      "password_confirmation"
    ).value;

    if (newPassword !== confirmPassword) {
      // Handle password mismatch error
      console.error("Passwords don't match");
      return;
    }

    try {
      const response = await axios.post(
        "http://127.0.0.1:8000/api/password-update",
        {
          password: newPassword,
          password_confirmation: confirmPassword,
        },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      alert("Password updated successfully");
      location.reload();
      // Handle success scenario: maybe show a success message or redirect
    } catch (error) {
      console.error("Password update failed:", error);
      // Handle failure scenario: display an error message or take appropriate action
    }
  };

  console.log(userData);
  return (
    <div className="min-h-screen">
      <Navbar />
      <div className="flex flex-col mt-28 mb">
        <div className="flex" aria-label="Breadcrumb">
          <ol className="inline-flex items-center pl-20 space-x-1 md:space-x-3 mb-4">
            <li className="inline-flex items-center">
              <a
                href="/"
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
              </a>
            </li>
            <li>
              <div className="flex items-center">
                <a
                  href="/catalog"
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
                    Profile
                  </span>
                </a>
              </div>
            </li>
          </ol>
        </div>
      </div>
      <div className="flex flex-row mx-20 gap-20 mb-12">
        <div className="flex flex-col grow">
          <div className="rounded-lg mb-8 bg-white p-6 shadow-md">
            <div className="text-left mb-4 font-bold text-lg">Edit Profile</div>
            <form className="flex flex-col" onSubmit={handleSubmit}>
              <div className="mb-4">
                <label
                  htmlFor="email"
                  className="block mb-2 text-sm font-medium text-gray-900 text-left"
                >
                  Email
                </label>
                <input
                  type="email"
                  id="email"
                  disabled
                  value={userData.email}
                  onChange={handleChange}
                  className="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                  required
                />
              </div>
              <div className="mb-4">
                <label
                  htmlFor="username"
                  className="block mb-2 text-sm font-medium text-gray-900 text-left"
                >
                  Username
                </label>
                <input
                  type="text"
                  id="username"
                  disabled
                  value={userData.username}
                  onChange={handleChange}
                  className="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                  required
                />
              </div>
              <div className="flex flex-row gap-8">
                <div className="mb-4 w-60">
                  <label
                    htmlFor="first_name"
                    className="block mb-2 text-sm font-medium text-gray-900 text-left"
                  >
                    First Name
                  </label>
                  <input
                    type="text"
                    id="first_name"
                    value={userData.first_name}
                    onChange={handleChange}
                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                    required
                  />
                </div>
                <div className="mb-4 w-60">
                  <label
                    htmlFor="last_name"
                    className="block mb-2 text-sm font-medium text-gray-900 text-left"
                  >
                    Last Name
                  </label>
                  <input
                    type="text"
                    id="last_name"
                    value={userData.last_name}
                    onChange={handleChange}
                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                    required
                  />
                </div>
              </div>
              <div className="flex flex-row gap-8">
                <div className="mb-4 w-60">
                  <label
                    htmlFor="gender"
                    className="block mb-4 text-sm font-medium text-gray-900 text-left"
                  >
                    Gender
                  </label>
                  <div className="flex flex-row justify-between mx-6">
                    <div className="flex items-center mb-4">
                      <input
                        id="gender-option-1"
                        type="radio"
                        name="gender"
                        value="M"
                        checked={userData.gender === "M"}
                        onChange={handleChange}
                        className="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600"
                      />
                      <label
                        htmlFor="gender-option-1"
                        className="block ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >
                        Man
                      </label>
                    </div>
                    <div className="flex items-center mb-4">
                      <input
                        id="gender-option-2"
                        type="radio"
                        name="gender"
                        value="W"
                        checked={userData.gender === "W"}
                        onChange={handleChange}
                        className="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600"
                      />
                      <label
                        htmlFor="gender-option-2"
                        className="block ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >
                        Woman
                      </label>
                    </div>
                  </div>
                </div>
                <div className="mb-4 w-60">
                  <label
                    htmlFor="telephone"
                    className="block mb-2 text-sm font-medium text-gray-900 text-left"
                  >
                    Telephone
                  </label>
                  <input
                    type="text"
                    id="telephone"
                    value={userData.telephone}
                    onChange={handleChange}
                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                    required
                  />
                </div>
              </div>
              <div className="mb-12">
                <label
                  htmlFor="address"
                  className="block mb-2 text-sm font-medium text-gray-900 text-left"
                >
                  Address
                </label>
                <input
                  type="text"
                  id="address"
                  value={userData.address}
                  onChange={handleChange}
                  className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full "
                  required
                />
              </div>
              <button
                type="submit"
                className="text-white w-80 bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-semibold rounded-lg text-sm sm:w-40 px-5 py-2.5 text-center place-self-end"
              >
                Save
              </button>
            </form>
          </div>
          <div className="rounded-lg mb-6 bg-white p-6 shadow-md">
            <div className="text-left mb-4 font-bold text-lg">
              Change Password
            </div>
            <form className="flex flex-col" onSubmit={handleChangePassword}>
              <div className="mb-4">
                <label
                  htmlFor="password"
                  className="block mb-2 text-sm font-medium text-gray-900 text-left"
                >
                  New Password
                </label>
                <input
                  type="password"
                  id="password"
                  className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                  required
                  pattern=".{8,}"
                  title="Must contain at least 8 or more characters"
                />
              </div>
              <div className="mb-12">
                <label
                  htmlFor="password"
                  className="block mb-2 text-sm font-medium text-gray-900 text-left"
                >
                  Confirm New Password
                </label>
                <input
                  type="password"
                  id="password_confirmation"
                  className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                  required
                  pattern=".{8,}"
                  title="Must contain at least 8 or more characters"
                />
              </div>
              <button
                type="submit"
                className="text-white w-80 bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-semibold rounded-lg text-sm sm:w-40 px-5 py-2.5 text-center place-self-end"
              >
                Save
              </button>
            </form>
          </div>
        </div>
        <div className="w-1/3 flex flex-col">
          <Link
            to="/shipment"
            className="mb-6 w-full tracking-wider text-white bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-semibold rounded-lg text-base px-5 py-5 text-center"
          >
            Your Shipment
          </Link>
          <Link
            to="/payment"
            className="mb-6 w-full tracking-wider text-white bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-semibold rounded-lg text-base px-5 py-5 text-center"
          >
            Your Payment
          </Link>
          <a
            href="#"
            onClick={handleLogout}
            className="mb-6 w-full tracking-wider text-white bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-semibold rounded-lg text-base px-5 py-5 text-center"
          >
            Logout
          </a>
          {userData.is_admin == 1 && (
            <Link
              to="/admin"
              className="mb-6 w-full tracking-wider text-white bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-semibold rounded-lg text-base px-5 py-5 text-center"
            >
              Admin Dashboard
            </Link>
          )}
        </div>
      </div>
      <Footer />
    </div>
  );
}

export default EditProfile;
