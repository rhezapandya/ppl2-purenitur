import React, { useState, useEffect } from "react";
import Footer from "../Components/Footer";
import Navbar from "../Components/Navbar";
import { Link } from "react-router-dom";
import Carousel from "../Components/Carousel";
import axios from "axios";
import CheckoutItem from "../Components/CheckoutItem";
import { MdOutlineShoppingCart } from "react-icons/md";
import { useNavigate } from "react-router-dom";

function Checkout() {
  const token = localStorage.getItem("token");
  const [userEmail, setUserEmail] = useState(null);
  const [userID, setUserID] = useState(null);
  const [userAddress, setUserAddress] = useState(null);
  const [cartData, setCartData] = useState([]);
  const [loading, setLoading] = useState(null);
  const [subtotal, setSubtotal] = useState(0);
  const [tax, setTax] = useState(0);
  const [finalPrice, setFinalPrice] = useState(0);
  const [discount, setDiscount] = useState(null);
  const [totalDiscount, setTotalDiscount] = useState("0");
  const [payMethod, setPayMethod] = useState("Bank Transfer BCA");
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
          setUserEmail(response.data.users.email);
          setUserID(response.data.users.id);
          setUserAddress(response.data.users.address);
        })
        .catch((error) => {
          console.error("Error fetching user profile:", error);
          // Handle error fetching user profile here
        });
    }
  }, []);

  useEffect(() => {
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
        })
        .catch((error) => {
          console.error("Error fetching cart:", error);
          // Handle error fetching cart here
        });
    }
  }, [token, userEmail]);

  const handleVoucherChange = (event) => {
    const selectedDiscount = event.target.value;
    setDiscount(selectedDiscount);
  };

  useEffect(() => {
    if (discount === "Diskon Libur Nasional") {
      const calculateDiscount = (subtotal * 15) / 100;
      setTotalDiscount(calculateDiscount);
    } else if (discount === "Diskon Ramadhan") {
      const calculateDiscount = (subtotal * 30) / 100;
      setTotalDiscount(calculateDiscount);
    } else if (discount === "Diskon Natal") {
      const calculateDiscount = (subtotal * 30) / 100;
      setTotalDiscount(calculateDiscount);
    } else if (discount === "Diskon Ulang Tahun PURENITUR") {
      const calculateDiscount = (subtotal * 50) / 100;
      setTotalDiscount(calculateDiscount);
    } else if (discount === "Diskon Cuci Gudang") {
      const calculateDiscount = (subtotal * 70) / 100;
      setTotalDiscount(calculateDiscount);
    } else {
      setTotalDiscount(0);
      setDiscount(null);
    }
  }, [discount, subtotal]);

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
      setFinalPrice((finalPriceValue - totalDiscount).toFixed(2));
    };

    if (cartData.length > 0) {
      calculateTotals();
    }
  }, [cartData, totalDiscount]);

  const handleCheckout = async (event) => {
    event.preventDefault();
    try {
      const payload = {
        user_id: userID,
        discount_name: discount,
        payment_method: payMethod,
        shipment_address: userAddress,
      };

      const response = await axios.post(
        "http://127.0.0.1:8000/api/checkout/create",
        payload,
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      // Handle success response
      console.log("Product checkout success:", response.data);
      navigate("/payment");
      // You might want to update UI or show a success message here
    } catch (error) {
      // Handle error response
      console.error("Error checkouting:", error);
      // You can display an error message or handle errors accordingly
    }
  };

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
            <li>
              <div className="flex items-center">
                <a
                  href="/cart"
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
                    Cart
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
                  Checkout
                </span>
              </div>
            </li>
          </ol>
        </div>
        {cartData ? (
          <div className="flex flex-col mx-20">
            <div className="flex flex-col rounded-lg justify-between mb-6 bg-white p-6 shadow-md sm:justify-start md:justify-start">
              <div className="flex flex-row items-center gap-2 pb-6">
                <MdOutlineShoppingCart size="25px" />
                <div className="font-bold">Order Summary</div>
              </div>
              {cartData &&
                cartData.map((cart) => (
                  <CheckoutItem
                    image={cart.image}
                    name={cart.name_product}
                    price={cart.price}
                    qty={cart.qty}
                    tax={cart.tax}
                  />
                ))}
              <div className="flex flex-col w-1/2 self-end">
                <div className="flex flex-row pb-6 items-center justify-between">
                  <div>Use Discount Voucher</div>
                  <select
                    id="voucher"
                    value={discount}
                    onChange={handleVoucherChange}
                    defaultValue="0"
                    className="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                  >
                    <option value="Diskon Libur Nasional">
                      Diskon Libur Nasional (15%)
                    </option>
                    <option value="Diskon Ramadhan">
                      Diskon Ramadhan (30%)
                    </option>
                    <option value="Diskon Natal">Diskon Natal (30%)</option>
                    <option value="Diskon Ulang Tahun PURENITUR">
                      Diskon Ulang Tahun PURENITUR (50%)
                    </option>
                    <option value="Diskon Cuci Gudang">
                      Diskon Cuci Gudang (70%)
                    </option>
                    <option value="0">Tidak Menggunakan Kupon Diskon</option>
                  </select>
                </div>
                <div className="font-bold text-left pb-6">Total Price</div>
                <div className="flex flex-row justify-between py-2">
                  <div className="">Subtotal</div>
                  <div>Rp. {subtotal}</div>
                </div>
                {discount && (
                  <div className="flex flex-row justify-between py-2">
                    <div className="">Discount</div>
                    <div>Rp. {totalDiscount}.00</div>
                  </div>
                )}
                <div className="flex flex-row justify-between py-2 pb-4 border-b-2">
                  <div className="">Tax</div>
                  <div>Rp. {tax}</div>
                </div>
                <div className="flex flex-row justify-between py-2 pt-4">
                  <div className="font-bold">Total</div>
                  <div className="font-bold">Rp. {finalPrice}</div>
                </div>
                <div className="text-right text-sm">Sudah Termasuk Pajak</div>
                <div className="flex flex-row py-2 pt-6"></div>
              </div>
            </div>

            <div className="flex flex-col mb-8">
              <p class="my-2 font-bold text-lg text-left">Payment Method</p>
              <div className="flex flex-row gap-8">
                <div class="bg-[#079992] rounded-md shadow-md w-1/2">
                  <ol class="text-white text-base p-8 pl-12 list-outside list-decimal text-left">
                    <li>
                      Checkout barang pada halaman ini untuk menerima kode
                      pembayaran.
                    </li>
                    <li>
                      Setelah menerima kode pembayaran, lakukan transfer pada
                      nomor rekening yang tersedia.
                    </li>
                    <li>Simpan tanda bukti pembayaran.</li>
                    <li>
                      Isi formulir untuk memberikan bukti pembayaran kepada{" "}
                      <b>Admin</b>.
                    </li>
                    <li>
                      Pembayaran akan dikonfirmasi paling lambat 1 x 24 jam,
                      pembayaran akan otomatis batal jika melebihi waktu yang
                      ditentukan.
                    </li>
                    <li>
                      Anda akan menerima email apabila pembayaran terkonfirmasi,
                      kemudian anda bisa melihat menu pengiriman pada menu{" "}
                      <b>Notifikasi</b>.
                    </li>
                  </ol>
                </div>
                <div class="card border border-slate-700 rounded-md w-1/2">
                  <div class="">
                    <div class="form-check"></div>

                    <div class="my-2">
                      <p className="text-left mb-2">
                        Untuk Sekarang, Payment Method Hanya Transfer
                      </p>

                      <p className="text-left mb-2">Alamat Pengiriman</p>
                      <input
                        value={userAddress}
                        className="px-2 py-1 mb-2 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                        disabled
                      />
                      <p class="text-left">Pastikan data-data sudah sesuai!</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="flex flex-row gap-8">
              <Link to="/cart" className="w-full mb-4">
                <button className="text-black w-full border-2 border-slate-700 focus:ring-4 focus:outline-none font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                  Go Back
                </button>
              </Link>
              <a href="#" className="w-full" onClick={handleCheckout}>
                <button className="text-white w-full border-2 border-teal-400 bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                  Checkout
                </button>
              </a>
            </div>
          </div>
        ) : (
          <div>
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
          </div>
        )}
      </div>
      <Footer />
    </div>
  );
}

export default Checkout;
