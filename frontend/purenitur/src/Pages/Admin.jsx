import React, { useState, useEffect } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
import Navbar from "../Components/Navbar";
import Footer from "../Components/Footer";

function Admin() {
  const token = localStorage.getItem("token");
  const [userData, setUserData] = useState(null);
  const [order, setOrder] = useState(null);
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

  useEffect(() => {
    if (userData && userData.is_admin === 0) {
      navigate("/");
    }
  }, [userData, navigate]);

  useEffect(() => {
    axios
      .get("http://127.0.0.1:8000/api/admin/orders", {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then((response) => {
        setOrder(response.data.order);
      })
      .catch((error) => {
        console.error("Error fetching order:", error);
        // Handle error fetching user profile here
      });
  }, []);

  const handleConfirm = (orderId) => {
    axios
      .post(
        "http://127.0.0.1:8000/api/checkout/payment/confirm",
        { order_id: orderId, status_payment: "CONFIRMED" },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      )
      .then(() => {
        setOrder((prevOrders) =>
          prevOrders.map((ord) =>
            ord.id === orderId ? { ...ord, status_payment: "CONFIRMED" } : ord
          )
        );
      })
      .catch((error) => {
        console.error("Error confirming payment:", error);
        // Handle error confirming payment
      });
  };

  const handleDecline = (orderId) => {
    axios
      .post(
        "http://127.0.0.1:8000/api/checkout/payment/failure",
        { order_id: orderId, status_payment: "FAILED" },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      )
      .then(() => {
        setOrder((prevOrders) =>
          prevOrders.map((ord) =>
            ord.id === orderId ? { ...ord, status_payment: "FAILED" } : ord
          )
        );
      })
      .catch((error) => {
        console.error("Error declining payment:", error);
        // Handle error declining payment
      });
  };

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
        <div className="mx-20">
          <table className="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead className="text-sm text-gray-700 uppercase bg-gray-50 text-center">
              <tr>
                <th className="px-6 py-3">ID</th>
                <th className="px-6 py-3">User ID</th>
                <th className="px-6 py-3">Total Price</th>
                <th className="px-6 py-3">Discount</th>
                <th className="px-6 py-3">Final Price</th>
                <th className="px-6 py-3">Transaction ID</th>
                <th className="px-6 py-3">Payment Type</th>
                <th className="px-6 py-3">Image</th>
                <th className="px-6 py-3">Payment Status</th>
                <th className="px-6 py-3">Address</th>
                <th className="px-6 py-3">Action</th>
              </tr>
            </thead>
            <tbody>
              {order &&
                order.map((ord, index) => (
                  <tr key={ord.id} className="bg-white border-b text-center">
                    <td className="px-6 py-4">{ord.id}</td>
                    <td className="px-6 py-4">{ord.user_id}</td>
                    <td className="px-6 py-4">{ord.price_total}</td>
                    <td className="px-6 py-4">{ord.discount_id}</td>
                    <td className="px-6 py-4">{ord.final_price}</td>
                    <td className="px-6 py-4">{ord.transaction_id}</td>
                    <td className="px-6 py-4">{ord.payment_type}</td>
                    <td className="px-6 py-4 text-indigo-600 hover:text-indigo-900">
                      <a href={`http://127.0.0.1:8000/${ord.image_payment}`}>
                        View Image
                      </a>
                    </td>
                    <td className="px-6 py-4">{ord.status_payment}</td>
                    <td className="px-6 py-4">{ord.shipment_address}</td>
                    <td>
                      <div className="flex flex-col gap-2">
                        <button
                          onClick={() => handleConfirm(ord.id)}
                          className="bg-green-500 text-white w-20 mx-auto hover:bg-green-600 px-2 py-1 rounded-md"
                        >
                          Confirm
                        </button>
                        <button
                          onClick={() => handleDecline(ord.id)}
                          className="bg-red-500 text-white w-20 mx-auto hover:bg-red-600 px-2 py-1 rounded-md"
                        >
                          Decline
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

export default Admin;
