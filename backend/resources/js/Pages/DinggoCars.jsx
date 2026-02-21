import { Head } from '@inertiajs/react'
import React from 'react';

const containerStylo = {
    display: 'grid',
    // Mobile: 1 column, Tablet: 2 columns, Desktop: 3-4 columns
    gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))',
    gap: '15px',
    padding: '20px',
    color: '#000',

    // Media queries for finer control
    '@media (maxWidth: 768px)': {
        gridTemplateColumns: '1fr',
    },
    '@media (minWidth: 769px) and (maxWidth: 1024px)': {
        gridTemplateColumns: 'repeat(2, 1fr)',
    },
    '@media (minWidth: 1025px)': {
        gridTemplateColumns: 'repeat(3, 1fr)',
    },
};
const boxStylo = {
    border: '1px solid #ccc',
    padding: '15px',
    borderRadius: '8px',
    backgroundColor: '#f9f9f9',
    boxShadow: '0 2px 4px rgba(0,0,0,0.1)',
    display: 'flex',
    flexDirection: 'column',
    color: '#000 !important',
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
