import { useState, useEffect } from "react";
import Slider from "react-slick";
import Card from "./Card";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import axios from "axios";

const Carousel = () => {
  const settings = {
    dots: false,
    infinite: true,
    speed: 500,
    slidesToShow: 5, // Set the number of cards to display at once
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 4, // Adjust the number of cards for larger screens if needed
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 3, // Adjust the number of cards for medium screens if needed
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1, // Adjust the number of cards for small screens if needed
          slidesToScroll: 1,
        },
      },
    ],
  };

  const [products, setProducts] = useState([]);

  const client = axios.create({
    baseURL: "http://127.0.0.1:8000/api/catalog",
  });

  useEffect(() => {
    client.get().then((response) => {
      setProducts(response.data.products);
    });
  }, []);

  return (
    <div className="container mx-auto">
      <Slider {...settings}>
        {products &&
          products.map((product) => (
            <div className="p-4">
              <Card
                name={product.name_product}
                id={product.id}
                category={product.category}
                price={product.price}
                image={product.image}
                rating={product.rating}
                sold={product.sold}
              />
            </div>
          ))}
      </Slider>
    </div>
  );
};

export default Carousel;
