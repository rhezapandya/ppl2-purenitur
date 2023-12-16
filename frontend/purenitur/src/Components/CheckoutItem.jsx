import React from "react";

function CheckoutItem({ qty, name, tax, price, image }) {
  return (
    <React.Fragment>
      <div className="flex flex-row items-center gap-8">
        <img
          src={image}
          className="h-36 w-36 my-2 border border-[#38ADA9] rounded-md p-2"
        />
        <div className="flex flex-col gap-4">
          <div className="text-left font-medium">Name : {name}</div>
          <div className="text-left font-medium">Quantity : {qty}</div>
          <div className="text-left font-medium">Base Price : Rp. {price}</div>
          <div className="text-left font-medium">Tax : Rp. {tax}</div>
        </div>
      </div>
      <hr class="h-1 my-8 bg-[#38ADA9] border-0 rounded-md"></hr>
    </React.Fragment>
  );
}

export default CheckoutItem;
