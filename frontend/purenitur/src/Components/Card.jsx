import { Link } from "react-router-dom";

function Card({ name, id, category, price, image, rating, sold }) {
  return (
    <Link to={"/catalog/product/" + name + "/" + id}>
      <div className="bg-white shadow-lg rounded-lg w-44 h-50 lg:w-56 lg:h-100">
        <img className="p-8 rounded-t-lg" src={image} alt="product image" />
        <div className="px-5 pb-5">
          <h3 className="text-gray-900 text-left font-semibold text-sm md:text-lg tracking-tigh md:mb-2">
            {name}
          </h3>
          <p className="text-sm md:text-md md:mb-2 text-left">{category}</p>
          <p className="text-sm md:text-base font-bold text-gray-900 md:mb-2 text-left">
            {price}
          </p>
          <div className="flex items-center">
            <svg
              className="w-5 h-5 text-yellow-300"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            <p className="text-sm md:text-base ml-2">
              {rating} | Terjual {sold}
            </p>
          </div>
        </div>
      </div>
    </Link>
  );
}

export default Card;
