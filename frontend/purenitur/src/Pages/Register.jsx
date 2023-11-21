/* eslint-disable no-unused-vars */
import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import logo from "../assets/img/Logo.png";
import { Link } from "react-router-dom";
import axios from "axios";

function Register() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    email: "",
    username: "",
    first_name: "",
    last_name: "",
    password: "",
    password_confirmation: "",
    gender: "",
    address: "",
    telephone: "",
  });
  const [notification, setNotification] = useState(null);

  const handleChange = (event) => {
    const { id, value, type } = event.target;

    if (type === "radio" && id === "gender-option-1") {
      setFormData((prevFormData) => ({
        ...prevFormData,
        gender: "M",
      }));
    } else if (type === "radio" && id === "gender-option-2") {
      setFormData((prevFormData) => ({
        ...prevFormData,
        gender: "W",
      }));
    } else {
      setFormData((prevFormData) => ({
        ...prevFormData,
        [id]: value,
      }));
    }
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      const response = await axios.post(
        "http://127.0.0.1:8000/api/users",
        formData
      );

      // Assuming successful creation returns a status of 200
      if (response.status === 200) {
        setNotification("Account created successfully!");
        setTimeout(() => {
          setNotification(null);
          navigate("/login"); // Redirect to the login page after notification
        }, 3000); // Redirect after 3 seconds (adjust this as needed)
      } else {
        // If the response status is not 200, handle the error
        const responseData = response.data;
        if (responseData?.message) {
          setNotification(responseData.message);
        } else {
          setNotification("Failed to create account. Please try again.");
        }
      }
    } catch (error) {
      // Handle other errors
      console.error("Error creating user:", error);
      setNotification("Failed to create account. Please try again.");
    }
  };

  return (
    <div className="h-screen flex flex-col my-auto justify-center">
      <div className="flex flex-col p-7 shadow-lg rounded-md max-w-lg items-center mx-auto my-10 px-20">
        <Link to="/">
          <img className="w-28 mb-6 mx-auto" src={logo} />
        </Link>
        <div className="font-semibold mb-4 text-lg">Welcome to Purenitur</div>
        {notification == "Account created successfully!" && (
          <div className="font-medium mb-4 text-sm text-left bg-green-200 p-2 rounded-md border-2 border-green-300">
            {notification}
          </div>
        )}
        {notification == "Failed to create account. Please try again." && (
          <div className="font-medium mb-4 text-sm text-left bg-red-200 p-2 rounded-md border-2 border-red-300">
            {notification}
          </div>
        )}
        <form onSubmit={handleSubmit}>
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
              value={formData.email}
              onChange={handleChange}
              className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
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
              value={formData.username}
              onChange={handleChange}
              className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
              required
            />
          </div>
          <div className="flex flex-row gap-8">
            <div className="mb-4">
              <label
                htmlFor="password"
                className="block mb-2 text-sm font-medium text-gray-900 text-left"
              >
                Password
              </label>
              <input
                type="password"
                id="password"
                value={formData.password}
                onChange={handleChange}
                pattern=".{8,}"
                title="Must contain at least 8 or more characters"
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                required
              />
            </div>
            <div className="mb-4">
              <label
                htmlFor="password_confirmation"
                className="block mb-2 text-sm font-medium text-gray-900 text-left"
              >
                Confirm Password
              </label>
              <input
                type="password"
                id="password_confirmation"
                value={formData.password_confirmation}
                onChange={handleChange}
                pattern=".{8,}"
                title="Must contain at least 8 or more characters"
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm
              rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                required
              />
            </div>
          </div>
          <div className="flex flex-row gap-8">
            <div className="mb-4">
              <label
                htmlFor="first_name"
                className="block mb-2 text-sm font-medium text-gray-900 text-left"
              >
                First Name
              </label>
              <input
                type="text"
                id="first_name"
                value={formData.first_name}
                onChange={handleChange}
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                required
              />
            </div>
            <div className="mb-4">
              <label
                htmlFor="last_name"
                className="block mb-2 text-sm font-medium text-gray-900 text-left"
              >
                Last Name
              </label>
              <input
                type="text"
                id="last_name"
                value={formData.last_name}
                onChange={handleChange}
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                required
              />
            </div>
          </div>
          <div className="flex flex-row justify-between">
            <div className="mb-4">
              <label
                htmlFor="gender"
                className="block mb-4 text-sm font-medium text-gray-900 text-left"
              >
                Gender
              </label>
              <div className="flex flex-row gap-10">
                <div className="flex items-center mb-4">
                  <input
                    id="gender-option-1"
                    type="radio"
                    name="gender"
                    value="M"
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
            <div className="mb-4">
              <label
                htmlFor="telephone"
                className="block mb-2 text-sm font-medium text-gray-900 text-left"
              >
                Telephone
              </label>
              <input
                type="text"
                id="telephone"
                value={formData.telephone}
                onChange={handleChange}
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                required
              />
            </div>
          </div>
          <div className="mb-6">
            <label
              htmlFor="address"
              className="block mb-2 text-sm font-medium text-gray-900 text-left"
            >
              Address
            </label>
            <input
              type="text"
              id="address"
              value={formData.address}
              onChange={handleChange}
              className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full "
              required
            />
          </div>
          <button
            type="submit"
            className="text-white w-80 bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm sm:w-40 px-5 py-2.5 text-center"
          >
            Sign Up
          </button>
        </form>
      </div>
    </div>
  );
}

export default Register;
