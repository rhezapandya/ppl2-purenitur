/* eslint-disable no-unused-vars */
import React from "react";
import logo from "../assets/img/Logo.png";
import { Link } from "react-router-dom";

function Register() {
  return (
    <div className="h-screen flex flex-col my-auto justify-center">
      <div className="flex flex-col p-7 shadow-lg rounded-md max-w-lg items-center mx-auto my-10 px-20">
        <Link to="/">
          <img className="w-28 mb-8 mx-auto" src={logo} />
        </Link>
        <div className="font-semibold mb-8 text-lg">Welcome to Purenitur</div>

        <htmlForm>
          <div className="mb-6">
            <label
              htmlFor="email"
              className="block mb-2 text-sm font-medium text-gray-900 text-left"
            >
              Email
            </label>
            <input
              type="email"
              id="email"
              className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
              required
            />
          </div>
          <div className="flex flex-row gap-8">
            <div className="mb-6">
              <label
                htmlFor="password"
                className="block mb-2 text-sm font-medium text-gray-900 text-left"
              >
                Password
              </label>
              <input
                type="password"
                id="password"
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                required
              />
            </div>
            <div className="mb-6">
              <label
                htmlFor="confirm password"
                className="block mb-2 text-sm font-medium text-gray-900 text-left"
              >
                Confirm Password
              </label>
              <input
                type="password"
                id="password"
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                required
              />
            </div>
          </div>
          <div className="flex flex-row gap-8">
            <div className="mb-6">
              <label
                htmlFor="first name"
                className="block mb-2 text-sm font-medium text-gray-900 text-left"
              >
                First Name
              </label>
              <input
                type="text"
                id="first-name"
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                required
              />
            </div>
            <div className="mb-6">
              <label
                htmlFor="last name"
                className="block mb-2 text-sm font-medium text-gray-900 text-left"
              >
                Last Name
              </label>
              <input
                type="text"
                id="last-name"
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
                    value="Man"
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
                    value="Woman"
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
            <div className="mb-6">
              <label
                htmlFor="telephone"
                className="block mb-2 text-sm font-medium text-gray-900 text-left"
              >
                Telephone
              </label>
              <input
                type="text"
                id="telephone"
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
        </htmlForm>
      </div>
    </div>
  );
}

export default Register;
