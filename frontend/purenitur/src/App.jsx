/* eslint-disable no-unused-vars */
import React from "react";
import { Routes, Route } from "react-router-dom";
import "./App.css";
import LandingPage from "./Pages/LandingPage";
import Login from "./Pages/Login";
import Register from "./Pages/Register";
import Catalog from "./Pages/Catalog";

function App() {
  return (
    <Routes>
      <Route path="/" exact element={<LandingPage />} />
      <Route path="/catalog" element={<Catalog />} />
      <Route path="/catalog/kursi" element={<Catalog category={"Kursi"} />} />
      <Route path="/catalog/meja" element={<Catalog category={"Meja"} />} />
      <Route path="/catalog/lampu" element={<Catalog category={"Lampu"} />} />
      <Route
        path="/catalog/tanaman"
        element={<Catalog category={"Tanaman"} />}
      />
      <Route path="/catalog/rak" element={<Catalog category={"Rak"} />} />
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Register />} />
    </Routes>
  );
}

export default App;
