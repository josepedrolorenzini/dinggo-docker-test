import { Head } from '@inertiajs/react'
import React from 'react';

const containerStylo = {
    display: 'grid',
    gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', // Auto-responsive
    gap: '15px',
    padding: '20px',
    color: '#000',
};

const boxStylo = {
    border: '1px solid #ccc',
    padding: '15px',
    borderRadius: '8px',
    backgroundColor: '#f9f9f9',
    boxShadow: '0 2px 4px rgba(0,0,0,0.1)',
    color: '#000',
};

function DinggoCars({ cars }) {
    return (
        <>
            <Head title="Dinggo Cars" />
            <div className="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
                <h1 style={{ textAlign: 'center', margin: '20px 0' }}>Dinggo Cars</h1>
                <div style={containerStylo}>
                    {cars && cars.map(car => (
                        <div style={boxStylo} key={car.vin}>
                            <h3 style={{ margin: '0 0 10px 0', color: '#333' }}>
                                {car.make} {car.model}
                            </h3>

                            {car.year && (
                                <p style={{ margin: '5px 0', color: '#666' }}>
                                    Year: {car.year}
                                </p>
                            )}

                            {car.colour && (
                                <p style={{ margin: '5px 0', color: '#666' }}>
                                    Color: {car.colour}
                                </p>
                            )}

                            {car.license_plate && (
                                <p style={{ margin: '5px 0', color: '#666' }}>
                                    Plate: {car.license_plate}
                                </p>
                            )}

                            {car.license_state && (
                                <p style={{ margin: '5px 0', color: '#666' }}>
                                    State: {car.license_state}
                                </p>
                            )}

                            <p style={{
                                margin: '5px 0',
                                fontSize: '12px',
                                color: '#999',
                                fontFamily: 'monospace',
                                backgroundColor: '#eee',
                                padding: '4px',
                                borderRadius: '4px'
                            }}>
                                VIN: {car.vin}
                            </p>
                        </div>
                    ))}
                </div>
            </div>
        </>
    )
}

export default DinggoCars
