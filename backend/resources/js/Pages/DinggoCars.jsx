import { Head } from '@inertiajs/react'
import React from 'react';

const containerStylo = {
    display: 'grid',
    // This creates 3 columns of equal width
    gridTemplateColumns: 'repeat(3, 1fr)',
    gap: '15px',
    padding: '20px',
    color: '#333',
};
const boxStylo = {
    border: '1px solid #ccc',
    padding: '15px',
    borderRadius: '8px',
    backgroundColor: '#f9f9f9',
    boxShadow: '0 2px 4px rgba(0,0,0,0.1)',
    display: 'flex',
    flexDirection: 'column'
};

function DinggoCars({ cars }) {
    return (
        <>
            <Head title="Dinggo Cars" />
            <div className="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
                <h1>Dinggo Cars</h1>
                {/* grid container */}
                <div style={containerStylo}>
                    <ul
                        className='list-disc list-inside'>
                        {cars && cars.map(car => (
                            <div
                                style={boxStylo}
                                key={car.vin}
                            >
                                <li key={car.vin}>{car.make} {car.model}</li>
                            </div>

                        ))}
                    </ul>
                </div>
            </div>
        </>
    )
}

export default DinggoCars
