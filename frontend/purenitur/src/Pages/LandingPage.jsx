/* eslint-disable no-unused-vars */
import React from "react";
import Content from "../Components/Content";
import Navbar from "../Components/Navbar";
import Carousel from "../Components/Carousel";
import Footer from "../Components/Footer";

function LandingPage() {
  return (
    <div className="flex flex-col">
      <Navbar />
      <Content />
      <Footer />
    </div>
  );
}

export default LandingPage;
