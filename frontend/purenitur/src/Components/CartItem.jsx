import React from "react";

function CartItem({ image, name, price, qty }) {
  return (
    <div class="rounded-lg w-full">
      <div class="justify-between mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start md:flex md:justify-start">
        <img
          src={image}
          alt="product-image"
          class="w-full rounded-lg sm:w-40"
        />
        <div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
          <div class="mt-3 sm:mt-0">
            <h2 class="text-lg font-bold text-gray-900 mr-4">{name}</h2>
          </div>
          <div class="mt-2 flex justify-between sm:space-y-6 sm:mt-0 sm:block sm:space-x-6">
            <div class="flex items-center border-gray-100">
              <form id="submits1">
                <input type="hidden" name="rowId" />
                <button
                  type="submit"
                  class="cursor-pointer rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-primary-1 hover:text-blue-50"
                >
                  -
                </button>
              </form>
              <span class="h-8 w-8 border bg-white text-center text-xs outline-none p-2">
                {qty}
              </span>
              <form id="submits2">
                <input type="hidden" />
                <button
                  type="submit"
                  class="cursor-pointer rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-primary-1 hover:text-blue-50"
                >
                  +
                </button>
              </form>
            </div>
            <div class="flex items-center space-x-4">
              <p class="mt-2 text-sm font-bold">Rp. {price}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default CartItem;
