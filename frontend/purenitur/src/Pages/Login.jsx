/* eslint-disable no-unused-vars */
import React, { useState } from "react";
import logo from "../assets/img/Logo.png";
import { Link } from "react-router-dom";
import axios from "axios";
import { useNavigate } from "react-router-dom";

function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);
  const navigate = useNavigate();

  const handleEmailChange = (e) => {
    setEmail(e.target.value);
  };

  const handlePasswordChange = (e) => {
    setPassword(e.target.value);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await axios.post("http://127.0.0.1:8000/api/login", {
        email,
        password,
      });
      if (response.status === 200) {
        const { token } = response.data;

        // Store token in local storage
        localStorage.setItem("token", token);
        navigate("/");
      } else {
        // Handle error responses (e.g., Email or Password is invalid)
        setError("Email or Password is invalid");
      }
    } catch (error) {
      setError("Email or Password is invalid");
    }
  };

  return (
    <div className="h-screen flex flex-col justify-center my-auto">
      <div className="flex flex-col p-7 shadow-lg rounded-md max-w-lg items-center mx-auto">
        <Link to="/">
          <img className="w-28 mb-8 mx-auto" src={logo} />
        </Link>
        <div className="font-semibold mb-8 text-lg">Good to see you again</div>
        {error == "Email or Password is invalid" && (
          <div className="font-medium mb-4 text-sm text-left bg-red-200 p-2 rounded-md border-2 border-red-300">
            {error}
          </div>
        )}
        <form onSubmit={handleSubmit}>
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
              value={email}
              onChange={handleEmailChange}
              className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
              required
            />
          </div>
          <div className="mb-8">
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
              value={password}
              onChange={handlePasswordChange}
              required
            />
          </div>
          <button
            type="submit"
            className="text-white w-80 bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm sm:w-40 px-5 py-2.5 text-center"
          >
            Submit
          </button>
        </form>
        <Link to="/register" className="text-sm font-medium mt-6">
          Register
        </Link>
      </div>
    </div>
  );
}

export default Login;
