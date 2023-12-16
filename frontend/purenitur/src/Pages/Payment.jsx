import React, { useState, useEffect } from "react";
import Footer from "../Components/Footer";
import Navbar from "../Components/Navbar";
import PaymentItem from "../Components/PaymentItem";
import axios from "axios";
import { useNavigate } from "react-router-dom";

function Payment() {
  const token = localStorage.getItem("token");
  const [userID, setUserID] = useState(null);
  const [paymentData, setPaymentData] = useState(null);
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
          setUserID(response.data.users.id);
        })
        .catch((error) => {
          console.error("Error fetching user profile:", error);
          // Handle error fetching user profile here
        });
    }
  }, [token]);

  useEffect(() => {
    if (userID) {
      axios
        .get("http://127.0.0.1:8000/api/checkout", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
          params: {
            user_id: userID,
          },
        })
        .then((response) => {
          setPaymentData(response.data.orders);
        })
        .catch((error) => {
          console.error("Error fetching payment:", error);
          // Handle error fetching cart here
        });
    }
  }, [userID]);

  console.log(paymentData);
  return (
    <div className="min-h-screen">
      <Navbar />
      <div className="flex flex-col mt-28 mb-8">
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
                    Store
                  </span>
                </a>
              </div>
            </li>
            <li aria-current="page">
              <div className="flex items-center">
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
                  Payment
                </span>
              </div>
            </li>
          </ol>
        </div>
        {paymentData && (
          <div className="flex flex-row mx-20 gap-10">
            <div className="flex flex-col w-1/2">
              {paymentData &&
                paymentData.map((payment) => (
                  <PaymentItem
                    key={payment.id}
                    order_id={payment.id}
                    status={payment.status_payment}
                    created_at={payment.created_at}
                  />
                ))}
            </div>
            <div className="rounded-lg bg-[#079992] p-6 shadow-md w-1/2 h-fit">
              <div className="font-bold text-base text-white pb-2 text-left">
                Payment Method
              </div>
              <ol className="px-4 text-white list-decimal list-outside text-left">
                <li>Pembayaran Hanya Transfer ke Bank BCA</li>
                <li>
                  Rekening PURENITUR
                  <br />
                  BCA 111111111 an PT PURENITUR
                </li>
                <li>Simpan tanda bukti pembayaran.</li>
                <li>Upload Foto bukti pembayaran pada Formulir di samping</li>
                <li>
                  Pembayaran akan dikonfirmasi paling lambat 1 x 24 jam,
                  pembayaran akan otomatis batal jika melebihi waktu yang
                  ditentukan.
                </li>
              </ol>
            </div>
          </div>
        )}
      </div>
      <Footer />
    </div>
  );
}

export default Payment;
