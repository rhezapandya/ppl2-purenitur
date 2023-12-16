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

function PaymentItem({ order_id, status, created_at }) {
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

  const handleSubmit = async () => {
    const formData = new FormData();
    formData.append("order_id", order_id);
    formData.append("image_payment", image);

    try {
      const response = await fetch(
        "http://127.0.0.1:8000/api/checkout/payment",
        {
          method: "POST",
          headers: {
            Authorization: `Bearer ${token}`, // Assuming user_login is the token
          },
          body: formData,
        }
      );

      const data = await response.json();
      // Handle the response data based on the API documentation
      if (response.ok) {
        // Handle success scenario
        // For example, show a success message to the user
        alert("Upload payment successful");
        location.reload();
      } else {
        // Handle error scenarios
        // For example, display an error message to the user
        console.error("Transaction failed", data);
      }
    } catch (error) {
      // Handle fetch errors
      console.error("Error occurred", error);
    }
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
          {currentStatus === "WAITING FOR CONFIRMATION" ? (
            <React.Fragment>
              <div className="flex items-center space-x-4">
                <div className="mt-4 text-base">
                  Bukti Pembayaran Sudah di Upload
                </div>
              </div>
              <div className="flex items-center space-x-4">
                <div className="mt-2 text-base">
                  Transaction ID : PRNTR-TR-{order_id}
                </div>
              </div>
              <button
                className="self-end mt-5 rounded-md bg-teal-400 hover:bg-teal-500 py-2.5 font-semibold px-8 text-sm text-white"
                onClick={() => navigate("/shipment")}
              >
                Go to Shipment
              </button>
            </React.Fragment>
          ) : (
            <React.Fragment>
              <div className="flex items-center space-x-4">
                <div className="mt-4 text-base">
                  Lakukan pembayaran dalam 24 jam dari {formattedTimestamp}
                </div>
              </div>
              <div className="flex items-center space-x-4">
                <div className="mt-2 text-base">Upload Bukti Pembayaran</div>
              </div>
              <div className="flex items-center space-x-4">
                <input
                  className="w-full mt-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 placeholder-red-400"
                  id="file_input"
                  type="file"
                  onChange={handleImageChange}
                  accept=".jpg,.jpeg,.png,.jfif,.pdf"
                />
              </div>
              {error && <p className="text-red-500">{error}</p>}
              <button
                onClick={handleSubmit}
                disabled={!image}
                className={`self-end mt-5 rounded-md ${
                  !image ? "bg-teal-300" : "bg-teal-400 hover:bg-teal-500"
                } py-2.5 font-semibold px-8 text-sm text-white`}
              >
                Upload
              </button>
            </React.Fragment>
          )}
        </div>
      </div>
    </div>
  );
}

export default PaymentItem;
