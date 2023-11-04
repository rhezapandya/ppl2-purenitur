import welcome from "../assets/img/Welcome.png";
import kursi from "../assets/img/kursi.png";
import meja from "../assets/img/meja.png";
import lampu from "../assets/img/lampu.jpg";
import tanaman from "../assets/img/tanaman.jfif";
import rak from "../assets/img/rak.png";
import kasur from "../assets/img/kasur.png";
import custom from "../assets/img/custom.png";
import Carousel from "./Carousel";

function Content() {
  return (
    <div className="flex flex-col mt-28">
      <div className="mb-12 lg:mx-64 md:mx-48 sm:mx-24 mx-12">
        <div className="relative flex w-full flex-wrap items-stretch border border-solid border-neutral-300 rounded-full px-3 lg:px-4">
          <input
            type="search"
            className="border-none focus:ring-0 relative m-0 block w-[1px] min-w-0 flex-auto px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:text-neutral-700 "
            placeholder="Search"
            aria-label="Search"
            aria-describedby="button-addon2"
          />

          <span
            className="input-group-text flex items-center whitespace-nowrap rounded px-3 py-1.5 text-center text-base font-normal text-neutral-700 dark:text-neutral-200"
            id="basic-addon2"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
              fill="currentColor"
              className="h-5 w-5"
            >
              <path
                fillRule="evenodd"
                d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                clipRule="evenodd"
              ></path>
            </svg>
          </span>
        </div>
      </div>
      <div className="flex flex-row justify-center gap-12 mb-12 lg:mx-64 md:mx-48 sm:mx-24 mx-12">
        <div className="w-1/2">
          <img src={welcome} alt="Welcome" />
        </div>
        <div className="flex flex-col gap-4">
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
          <a href="#">
            <img className="rounded-lg" src={kursi} alt="image description" />
          </a>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Kursi
          </figcaption>
        </figure>
        <figure className="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale-0 hover:grayscale">
          <a href="#">
            <img className="rounded-lg" src={meja} alt="image description" />
          </a>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Meja
          </figcaption>
        </figure>
        <figure className="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale-0 hover:grayscale">
          <a href="#">
            <img className="rounded-lg" src={lampu} alt="image description" />
          </a>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Lampu
          </figcaption>
        </figure>
        <figure className="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale-0 hover:grayscale">
          <a href="#">
            <img className="rounded-lg" src={tanaman} alt="image description" />
          </a>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Tanaman
          </figcaption>
        </figure>
        <figure className="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale-0 hover:grayscale">
          <a href="#">
            <img className="rounded-lg" src={rak} alt="image description" />
          </a>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Rak
          </figcaption>
        </figure>
        <figure className="relative max-w-sm transition-all duration-300 cursor-pointer filter grayscale-0 hover:grayscale">
          <a href="#">
            <img className="rounded-lg" src={kasur} alt="image description" />
          </a>
          <figcaption className="absolute text-2xl text-white font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            Katalog
          </figcaption>
        </figure>
      </div>
      <div className="mb-12">
        <Carousel />
      </div>
      <div className="bg-[#60A3BC] p-6">
        <div className="flex justify-center mb-9">
          <h1 className="font-semibold text-white text-md lg:text-xl md:text-lg text-center">
            Rancang Sendiri Furnitur Untuk Hunian Anda
          </h1>
        </div>
        <div className="flex justify-center mb-9">
          <img src={custom} alt="" />
        </div>
        <div className="flex justify-center mb-9">
          <a
            href="/customization"
            className="text-white text-base font-semibold bg-teal-400 hover:bg-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-300 rounded-full py-2 px-12 text-center mr-2 mb-2"
          >
            Lihat Halaman
          </a>
        </div>
      </div>
    </div>
  );
}

export default Content;
