import welcome from "../assets/img/Welcome.png";
import kursi from "../assets/img/kursi.png";
import meja from "../assets/img/meja.png";
import lampu from "../assets/img/lampu.jpg";
import tanaman from "../assets/img/tanaman.jfif";
import rak from "../assets/img/rak.png";
import kasur from "../assets/img/kasur.png";
import custom from "../assets/img/custom.png";
import Carousel from "./Carousel";
import { Link } from "react-router-dom";

function Content() {
  return (
    <div className="flex flex-col mt-28">
      <div className="flex flex-row justify-center gap-12 mb-12 lg:mx-64 md:mx-48 sm:mx-24 mx-12">
        <div className="w-1/2">
          <img src={welcome} alt="Welcome" />
        </div>
        <div className="flex flex-col gap-4 mt-8">
          <div className="text-2xl font-bold">
            Bangun Rumah Impianmu
            <br />
            Bersama Kami
          </div>
          <div className="text-lg">
            Kami menyediakan furnitur lokal dengan kualitas
            <br />
            terbaik untuk membuat hunian impian anda
          </div>
        </div>
      </div>
      <div className="grid grid-cols-2 mx-auto gap-4 mb-12">
        <figure className="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale-0 hover:grayscale">
          <Link to="/catalog/kursi">
            <img className="rounded-lg" src={kursi} alt="image description" />
          </Link>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Kursi
          </figcaption>
        </figure>
        <figure className="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale-0 hover:grayscale">
          <Link to="/catalog/meja">
            <img className="rounded-lg" src={meja} alt="image description" />
          </Link>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Meja
          </figcaption>
        </figure>
        <figure className="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale-0 hover:grayscale">
          <Link to="/catalog/lampu">
            <img className="rounded-lg" src={lampu} alt="image description" />
          </Link>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Lampu
          </figcaption>
        </figure>
        <figure className="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale-0 hover:grayscale">
          <Link to="/catalog/tanaman">
            <img className="rounded-lg" src={tanaman} alt="image description" />
          </Link>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Tanaman
          </figcaption>
        </figure>
        <figure className="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale-0 hover:grayscale">
          <Link to="/catalog/rak">
            <img className="rounded-lg" src={rak} alt="image description" />
          </Link>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Rak
          </figcaption>
        </figure>
        <figure className="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale-0 hover:grayscale">
          <Link to="/catalog">
            <img className="rounded-lg" src={kasur} alt="image description" />
          </Link>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Katalog
          </figcaption>
        </figure>
      </div>
      <div className="mb-12">
        <Carousel />
      </div>
    </div>
  );
}

export default Content;
