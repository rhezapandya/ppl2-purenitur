/* eslint-disable no-unused-vars */
import React from "react";
import logo from "../assets/img/Logo.png";
import Register from "./Register";
import { Routes, Route, Link } from "react-router-dom";

function Login() {
  return (
    <div className="h-screen flex flex-col justify-center my-auto">
      <div className="flex flex-col p-7 shadow-lg rounded-md max-w-lg items-center mx-auto">
        <Link to="/">
          <img className="w-28 mb-8 mx-auto" src={logo} />
        </Link>
        <div className="font-semibold mb-8 text-lg">Good to see you again</div>

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
              className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
              required
            />
          </div>
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
              className="bg-gray-50 w-96 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
              required
            />
          </div>
          <div className="flex items-start mb-8">
            <div className="flex items-center h-5">
              <input
                id="remember"
                type="checkbox"
                value=""
                className="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300"
                required
              />
            </div>
            <label
              htmlFor="remember"
              className="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
            >
              Remember me
            </label>
          </div>
          <button
            type="submit"
            className="text-white w-80 bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm sm:w-40 px-5 py-2.5 text-center"
          >
            Submit
          </button>
        </htmlForm>
        <Link to="/register" className="text-sm font-medium mt-6">
          Register
        </Link>
      </div>
    </div>
  );
}

export default Login;
