import Slider from "react-slick";
import Card from "./Card";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

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

  return (
    <div className="container mx-auto">
      <Slider {...settings}>
        <div className="p-4">
          <Card />
        </div>
        <div className="p-4">
          <Card />
        </div>
        <div className="p-4">
          <Card />
        </div>
        <div className="p-4">
          <Card />
        </div>
        <div className="p-4">
          <Card />
        </div>
        <div className="p-4">
          <Card />
        </div>
        <div className="p-4">
          <Card />
        </div>
        <div className="p-4">
          <Card />
        </div>
        <div className="p-4">
          <Card />
        </div>
        <div className="p-4">
          <Card />
        </div>
        <div className="p-4">
          <Card />
        </div>
        <div className="p-4">
          <Card />
        </div>
      </Slider>
    </div>
  );
};

export default Carousel;
