import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

function formatTimestamp(timestamp) {
  const date = new Date(timestamp);
  const formattedDate = date
    .toLocaleString("en-US", {
      year: "numeric",
      month: "2-digit",
      day: "2-digit",
      hour: "2-digit",
      minute: "2-digit",
      second: "2-digit",
      hour12: false,
    })
    .replace(",", ""); // Remove the comma after the date

  return formattedDate;
}

function ShipmentItem({ order_id, status, created_at }) {
  const formattedTimestamp = formatTimestamp(created_at);
  const token = localStorage.getItem("token");
  const [image, setImage] = useState(null);
  const [error, setError] = useState(null);
  const [currentStatus, setCurrentStatus] = useState(status);
  const navigate = useNavigate();

  const handleImageChange = (event) => {
    const selectedImage = event.target.files[0];
    const allowedTypes = [
      "image/jpeg",
      "image/png",
      "image/jpg",
      "image/jfif",
      "application/pdf",
    ];

    if (selectedImage && allowedTypes.includes(selectedImage.type)) {
      setImage(selectedImage);
      setError(null);
    } else {
      setImage(null);
      setError(
        "Invalid file type. Please select a JPEG, JPG, PNG, JFIF, or PDF file."
      );
    }
  };

  const handleSubmit = () => {
    navigate("/payment");
  };

  return (
    <div className="rounded-lg w-full">
      <div className="justify-between mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start md:flex md:justify-start">
        <div className="flex flex-col w-full">
          <div className="flex items-center space-x-4">
            <div className="text-lg font-bold">
              ORDER ID : PRNTR-ORD-{order_id}
            </div>
          </div>
          <div className="flex items-center space-x-4">
            <div className="text-lg mt-2 font-bold">
              Status Pembayaran : {status}
            </div>
          </div>
          {currentStatus === "WAITING FOR CONFIRMATION" && (
            <React.Fragment>
              <div className="flex items-center space-x-4">
                <div className="mt-2 text-base">
                  Transaction ID : PRNTR-TR-{order_id}
                </div>
              </div>
              <ol class="relative border-s border-gray-300 mt-4">
                <li class="mb-6 ms-4 text-left">
                  <div class="absolute w-3 h-3 bg-black rounded-full mt-1.5 -start-1.5 border border-white"></div>
                  <time class="mb-1 text-sm font-normal leading-none">
                    Menunggu Konfirmasi Pembayaran
                  </time>
                </li>
              </ol>
            </React.Fragment>
          )}
          {currentStatus === "WAITING FOR PAYMENT" && (
            <React.Fragment>
              <div className="flex items-center space-x-4">
                <div className="mt-4 text-base">
                  Lakukan pembayaran dalam 24 jam dari {formattedTimestamp}
                </div>
              </div>
              <button
                onClick={handleSubmit}
                className="self-end mt-5 rounded-md bg-teal-400 hover:bg-teal-500
                py-2.5 font-semibold px-8 text-sm text-white"
              >
                Go to Payment
              </button>
            </React.Fragment>
          )}
        </div>
      </div>
    </div>
  );
}

export default ShipmentItem;
