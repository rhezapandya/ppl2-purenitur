import React, { useState, useEffect } from "react";
import Footer from "../Components/Footer";
import Navbar from "../Components/Navbar";
import { Link, useNavigate } from "react-router-dom";
import Carousel from "../Components/Carousel";
import axios from "axios";
import CartItem from "../Components/CartItem";

function ShoppingCart() {
  const token = localStorage.getItem("token");
  const navigate = useNavigate();
  const [userEmail, setUserEmail] = useState(null);
  const [userAddress, setUserAddress] = useState(null);
  const [cartData, setCartData] = useState([]);
  const [loading, setLoading] = useState(null);
  const [subtotal, setSubtotal] = useState(0);
  const [tax, setTax] = useState(0);
  const [finalPrice, setFinalPrice] = useState(0);

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
          setUserEmail(response.data.users.email);
          setUserAddress(response.data.users.address);
        })
        .catch((error) => {
          console.error("Error fetching user profile:", error);
          // Handle error fetching user profile here
        });
    }
  }, [token, navigate]);

  useEffect(() => {
    setLoading(true);
    if (token) {
      axios
        .get("http://127.0.0.1:8000/api/cart", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
          params: {
            email: userEmail,
          },
        })
        .then((response) => {
          setCartData(Object.values(response.data.carts));
          setLoading(false);
        })
        .catch((error) => {
          console.error("Error fetching cart:", error);
          // Handle error fetching cart here
        });
    }
  }, [token, userEmail]);

  useEffect(() => {
    const calculateTotals = () => {
      let subTotalValue = 0;
      let taxValue = 0;
      let finalPriceValue = 0;

      cartData.forEach((item) => {
        subTotalValue += parseFloat(item.price.replace(",", ""));
        taxValue += parseFloat(item.tax.replace(",", ""));
        finalPriceValue += parseFloat(item.subtotal.replace(",", ""));
      });

      setSubtotal(subTotalValue.toFixed(2));
      setTax(taxValue.toFixed(2));
      setFinalPrice(finalPriceValue.toFixed(2));
    };

    if (cartData.length > 0) {
      calculateTotals();
    }
  }, [cartData]);

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
                  Cart
                </span>
              </div>
            </li>
          </ol>
        </div>
        {loading ? (
          <div className="mb-12"></div>
        ) : cartData.length !== 0 ? (
          <React.Fragment>
            <div className="flex flex-row mx-20 gap-8 mb-12">
              <div className="flex flex-col grow">
                {cartData &&
                  cartData.map((cart) => (
                    <CartItem
                      image={cart.image}
                      name={cart.name_product}
                      price={cart.price}
                      qty={cart.qty}
                    />
                  ))}
              </div>
              <div class="w-1/3">
                <div class="rounded-lg justify-between mb-6 bg-white p-6 shadow-md sm:justify-start md:justify-start">
                  <div className="flex flex-col">
                    <div className="flex flex-row justify-between py-2">
                      <div className="">Subtotal</div>
                      <div>Rp. {subtotal}</div>
                    </div>
                    <div className="flex flex-row justify-between py-2 pb-4 border-b-2">
                      <div className="">Tax</div>
                      <div>Rp. {tax}</div>
                    </div>
                    <div className="flex flex-row justify-between py-2 pt-4">
                      <div className="font-bold">Total</div>
                      <div className="font-bold">Rp. {finalPrice}</div>
                    </div>
                    <div className="text-right text-sm">
                      Sudah Termasuk Pajak
                    </div>
                    <div className="flex flex-row py-2 pt-6">
                      <Link to="/checkout" className="w-full">
                        <button className="text-white w-full bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                          Checkout
                        </button>
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="mb-12">
              <Carousel />
            </div>
          </React.Fragment>
        ) : (
          <React.Fragment>
            <div class="my-12 rounded-lg border bg-white p-6 shadow-md md:w-1/3 self-center">
              <div class="mb-2 text-center">
                <p class="text-gray-700">
                  Shopping Cart masih Kosong. <br />
                  Yuk Belanja Dulu!
                </p>
              </div>
              <Link to="/catalog">
                <button class="w-full rounded-md bg-teal-400 hover:bg-teal-500 py-1.5 font-medium text-white">
                  Go to Catalog
                </button>
              </Link>
            </div>
            <div className="mb-12">
              <Carousel />
            </div>
          </React.Fragment>
        )}
      </div>
      <Footer />
    </div>
  );
}

export default ShoppingCart;
