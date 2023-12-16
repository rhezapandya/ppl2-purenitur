import React from "react";
import Navbar from "../Components/Navbar";
import { useParams, useNavigate } from "react-router-dom";
import { useState, useEffect } from "react";
import axios from "axios";
import Card from "../Components/Card";
import Footer from "../Components/Footer";

function ProductDetail() {
  const { name, id } = useParams();
  const [productDetails, setProductDetails] = useState(null);
  const [loading, setLoading] = useState(true);
  const [selectedProduct, setSelectedProduct] = useState([]);
  const [userEmail, setUserEmail] = useState(null);
  const token = localStorage.getItem("token");
  const navigate = useNavigate();

  useEffect(() => {
    const response = axios
      .get(`http://127.0.0.1:8000/api/product-detail/${name}`)
      .then((response) => {
        setSelectedProduct(
          response.data.products.filter((product) => {
            return product.id == id;
          })[0]
        );
        setProductDetails(response.data);
        setLoading(false);
      });
  }, [name]);

  useEffect(() => {
    // Fetch user profile using token from localStorage
    if (token) {
      axios
        .get("http://127.0.0.1:8000/api/profile", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        })
        .then((response) => {
          setUserEmail(response.data.users.email);
        })
        .catch((error) => {
          console.error("Error fetching user profile:", error);
          // Handle error fetching user profile here
        });
    }
  }, []);

  const handleAddToCart = async (event) => {
    event.preventDefault();
    try {
      if (!userEmail) {
        // Handle case where user email is not available
        console.error("User email not found.");
        navigate("/login");
        return;
      }

      const payload = {
        email: userEmail,
        item_id: selectedProduct.id,
        name_product: selectedProduct.name_product,
        price: selectedProduct.price,
      };

      const response = await axios.post(
        "http://127.0.0.1:8000/api/cart/add",
        payload,
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      // Handle success response
      console.log("Product added to cart:", response.data);
      navigate("/cart");
      // You might want to update UI or show a success message here
    } catch (error) {
      // Handle error response
      console.error("Error adding product to cart:", error);
      // You can display an error message or handle errors accordingly
    }
  };

  return (
    <div className="min-h-screen">
      <Navbar />
      <div className="flex flex-col mt-28">
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
                  Details
                </span>
              </div>
            </li>
          </ol>
        </div>
        {!loading && selectedProduct && (
          <>
            <div className="grid grid-cols-2 mt-10 mx-auto gap-8">
              <div class="relative">
                <img
                  src={selectedProduct.image}
                  alt=""
                  class="mx-auto mb-3 md:mb-4 h-[360px]"
                />
              </div>
              <div class="flex flex-col">
                <div class="w-full md:h-[360px] mb-4">
                  <div class="font-bold text-2xl mb-3 text-left">
                    Detail Barang {selectedProduct.name_product}
                  </div>
                  <div class="font-semibold text-lg mb-1 text-left">Harga</div>
                  <div class="font-semibold text-xl mb-3 text-left">
                    Rp. {selectedProduct.price}
                  </div>
                  <div class="font-semibold text-lg mb-1 text-left">
                    Nomor SKU
                  </div>
                  <div class="font-regular text-base mb-3 text-left">
                    {selectedProduct.sku}
                  </div>
                  <div class="font-semibold text-lg mb-1 text-left">Rating</div>
                  <div class="flex items-center mb-3">
                    <svg
                      class="w-5 h-5 text-yellow-300"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <div class="font-regular text-base">
                      {selectedProduct.rating}
                    </div>
                  </div>
                  <div class="font-semibold text-lg mb-1 text-left">
                    Terjual
                  </div>
                  <div class="font-regular text-base mb-3 text-left">
                    {selectedProduct.sold}
                  </div>
                </div>
                <div class="w-full flex items-stretch">
                  <form onSubmit={(event) => handleAddToCart(event)}>
                    <button
                      type="submit"
                      class="w-44 md:w-[200px] bg-white rounded-lg p-3 text-primary-one font-semibold border-2 border-primary-one mr-1"
                    >
                      Add To Cart
                    </button>
                  </form>
                </div>
              </div>
              <div class="w-full text-left mt-20">
                <div class="font-semibold text-lg mb-1">Features:</div>
                <ul>
                  {selectedProduct.feature_1 && (
                    <li>- {selectedProduct.feature_1}</li>
                  )}
                  {selectedProduct.feature_2 && (
                    <li>- {selectedProduct.feature_2}</li>
                  )}
                  {selectedProduct.feature_3 && (
                    <li>- {selectedProduct.feature_3}</li>
                  )}
                  {selectedProduct.feature_4 && (
                    <li>- {selectedProduct.feature_4}</li>
                  )}
                </ul>
              </div>
            </div>
            <div className="flex flex-col mt-20">
              <div className="font-semibold text-md lg:text-xl md:text-lg text-center mb-10">
                Hadir Untuk Hunian Anda
              </div>
              <div className="grid grid-cols-5 gap-6 mx-20 mb-10">
                {productDetails.catalogs.map((product) => (
                  <Card
                    name={product.name_product}
                    id={product.id}
                    category={product.category}
                    price={product.price}
                    image={product.image}
                    rating={product.rating}
                    sold={product.sold}
                  />
                ))}
              </div>
            </div>
          </>
        )}
      </div>
      <Footer />
    </div>
  );
}

export default ProductDetail;
