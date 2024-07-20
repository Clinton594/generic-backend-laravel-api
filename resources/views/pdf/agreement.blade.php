<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>PDF</title>
        <style>
            body {
                font-family: "Inter", sans-serif;
                max-width: 1000px;
                margin-left: auto;
                margin-right: auto;
            }

            @page {
                size: A4;
                margin: 0;
            }
            @media print {
                html,
                body {
                    width: 210mm;
                    height: 297mm;
                }
            }

            table {
                border-collapse: collapse;
            }

            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
                font-size: 10px;
            }

            .table th,
            .table td {
                padding: 0.3rem;
                vertical-align: top;
                border-top: 1px solid #dee2e6;
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }

            .table tbody + tbody {
                border-top: 2px solid #dee2e6;
            }

            .table-sm th,
            .table-sm td {
                padding: 0.3rem;
            }

            .table-bordered {
                border: 1px solid #dee2e6;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #dee2e6;
            }

            .table-bordered thead th,
            .table-bordered thead td {
                border-bottom-width: 2px;
            }

            .table-borderless th,
            .table-borderless td,
            .table-borderless thead th,
            .table-borderless tbody + tbody {
                border: 0;
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0, 0, 0, 0.05);
            }

            .table-hover tbody tr:hover {
                color: #212529;
                background-color: rgba(0, 0, 0, 0.075);
            }

            .table-primary,
            .table-primary > th,
            .table-primary > td {
                background-color: #b8daff;
            }

            .table-primary th,
            .table-primary td,
            .table-primary thead th,
            .table-primary tbody + tbody {
                border-color: #7abaff;
            }

            .table-hover .table-primary:hover {
                background-color: #9fcdff;
            }

            .table-hover .table-primary:hover > td,
            .table-hover .table-primary:hover > th {
                background-color: #9fcdff;
            }

            .table-secondary,
            .table-secondary > th,
            .table-secondary > td {
                background-color: #d6d8db;
            }

            .table-secondary th,
            .table-secondary td,
            .table-secondary thead th,
            .table-secondary tbody + tbody {
                border-color: #b3b7bb;
            }

            .table-hover .table-secondary:hover {
                background-color: #c8cbcf;
            }

            .table-hover .table-secondary:hover > td,
            .table-hover .table-secondary:hover > th {
                background-color: #c8cbcf;
            }

            .table-success,
            .table-success > th,
            .table-success > td {
                background-color: #c3e6cb;
            }

            .table-success th,
            .table-success td,
            .table-success thead th,
            .table-success tbody + tbody {
                border-color: #8fd19e;
            }

            .table-hover .table-success:hover {
                background-color: #b1dfbb;
            }

            .table-hover .table-success:hover > td,
            .table-hover .table-success:hover > th {
                background-color: #b1dfbb;
            }

            .table-info,
            .table-info > th,
            .table-info > td {
                background-color: #bee5eb;
            }

            .table-info th,
            .table-info td,
            .table-info thead th,
            .table-info tbody + tbody {
                border-color: #86cfda;
            }

            .table-hover .table-info:hover {
                background-color: #abdde5;
            }

            .table-hover .table-info:hover > td,
            .table-hover .table-info:hover > th {
                background-color: #abdde5;
            }

            .table-warning,
            .table-warning > th,
            .table-warning > td {
                background-color: #ffeeba;
            }

            .table-warning th,
            .table-warning td,
            .table-warning thead th,
            .table-warning tbody + tbody {
                border-color: #ffdf7e;
            }

            .table-hover .table-warning:hover {
                background-color: #ffe8a1;
            }

            .table-hover .table-warning:hover > td,
            .table-hover .table-warning:hover > th {
                background-color: #ffe8a1;
            }

            .table-danger,
            .table-danger > th,
            .table-danger > td {
                background-color: #f5c6cb;
            }

            .table-danger th,
            .table-danger td,
            .table-danger thead th,
            .table-danger tbody + tbody {
                border-color: #ed969e;
            }

            .table-hover .table-danger:hover {
                background-color: #f1b0b7;
            }

            .table-hover .table-danger:hover > td,
            .table-hover .table-danger:hover > th {
                background-color: #f1b0b7;
            }

            .table-light,
            .table-light > th,
            .table-light > td {
                background-color: #fdfdfe;
            }

            .table-light th,
            .table-light td,
            .table-light thead th,
            .table-light tbody + tbody {
                border-color: #fbfcfc;
            }

            .table-hover .table-light:hover {
                background-color: #ececf6;
            }

            .table-hover .table-light:hover > td,
            .table-hover .table-light:hover > th {
                background-color: #ececf6;
            }

            .table-dark,
            .table-dark > th,
            .table-dark > td {
                background-color: #000;
            }

            .table-dark th,
            .table-dark td,
            .table-dark thead th,
            .table-dark tbody + tbody {
                border-color: #95999c;
            }

            .table-hover .table-dark:hover {
                background-color: #b9bbbe;
            }

            .table-hover .table-dark:hover > td,
            .table-hover .table-dark:hover > th {
                background-color: #b9bbbe;
            }

            .table-active,
            .table-active > th,
            .table-active > td {
                background-color: rgba(0, 0, 0, 0.075);
            }

            .table-hover .table-active:hover {
                background-color: rgba(0, 0, 0, 0.075);
            }

            .table-hover .table-active:hover > td,
            .table-hover .table-active:hover > th {
                background-color: rgba(0, 0, 0, 0.075);
            }

            .table .thead-dark th {
                color: #fff;
                background-color: #343a40;
                border-color: #454d55;
            }

            .table .thead-light th {
                color: #495057;
                background-color: #e9ecef;
                border-color: #dee2e6;
            }

            .table-dark {
                color: #fff;
                background-color: #343a40;
            }

            .table-dark th,
            .table-dark td,
            .table-dark thead th {
                border-color: #454d55;
            }

            .table-dark.table-bordered {
                border: 0;
            }

            .table-dark.table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(255, 255, 255, 0.05);
            }

            .table-dark.table-hover tbody tr:hover {
                color: #fff;
                background-color: rgba(255, 255, 255, 0.075);
            }

            @media (max-width: 575.98px) {
                .table-responsive-sm {
                    display: block;
                    width: 100%;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }
                .table-responsive-sm > .table-bordered {
                    border: 0;
                }
            }

            @media (max-width: 767.98px) {
                .table-responsive-md {
                    display: block;
                    width: 100%;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }
                .table-responsive-md > .table-bordered {
                    border: 0;
                }
            }

            @media (max-width: 991.98px) {
                .table-responsive-lg {
                    display: block;
                    width: 100%;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }
                .table-responsive-lg > .table-bordered {
                    border: 0;
                }
            }

            @media (max-width: 1199.98px) {
                .table-responsive-xl {
                    display: block;
                    width: 100%;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }
                .table-responsive-xl > .table-bordered {
                    border: 0;
                }
            }

            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-responsive > .table-bordered {
                border: 0;
            }

            .mt-5 {
                margin-top: 1.5rem;
            }
            .section {
                display: table;
                width: 100%;
            }
            .cell {
                display: table-cell;
            }
            .alignleft {
                float: left;
            }
            .alignright {
                float: right;
            }
        </style>
    </head>
    <body>
        <div class="mt-5">
            <div id="textbox mt-5" style="padding-top: 30px">
                <div class="alignleft">
                    <img src="https://paidby.app/new-logo.svg" width="100px" />
                </div>
                <h3 class="alignright"><b>Engagement Contract</b></h3>
                <div style="clear: both"></div>
            </div>
            <div style="margin-top: 50px">
                <table class="table table-bordered mb-5">
                    <thead class="">
                        <tr class="table-dark">
                            <p>
                                This Engagement Contract ("<b
                                    >Agreement" hereinafter</b
                                >") has been signed between the parties below,
                                on _____________________, under the indicated
                                terms and conditions.
                            </p>
                            <p>
                                _________________________, of legal age,
                                residing at __________________,
                                __________________, __________________,
                                __________________, (hereinafter shall be
                                referred as the "<b>CONTRACTOR</b>");
                            </p>
                            <p style="text-align: center; padding: 10px 0">
                                <b> - and -</b>
                            </p>
                            <p>
                                _______________________, legally capable,
                                residing at __________________
                                __________________ __________________,
                                __________________, (hereinafter shall be
                                referred as the "<b>Contract Owner</b>");
                            </p>
                            <p style="text-align: center; padding: 10px 0">
                                <b> WITNESSETH: That -</b>
                            </p>
                            <p>
                                WHEREAS, the CONTRACTOR has an existing
                                obligation to the CONTRACT OWNER for an amount
                                of __________________ under a Contract
                                Agreement.
                            </p>
                            <p>
                                WHEREAS, the CONTRACTOR and the CONTRACT OWNER,
                                by the goodwill of both parties, desire to
                                secure the amount of debt __________________ by
                                debt restructuring with this Agreement under the
                                terms and conditions herein provided;
                            </p>
                            <p>
                                NOW, THEREFORE, for and in consideration of the
                                foregoing premises, the parties hereto agree as
                                follows:
                            </p>
                            <div style="margin-top: 40px">
                                <p><b>1. Payment Plan</b></p>
                                <p>
                                    The Parties agree that the total debt,
                                    _____________ shall be paid in accordance
                                    with the Payment Plan which is an integral
                                    part of this Agreement, indicated below. The
                                    CONTRACTOR accepts, declares and undertakes
                                    to fufill the terms of this contract in full
                                    on or before the dates specified in the
                                    Payment Plan.
                                </p>
                            </div>
                            <div style="margin-top: 40px">
                                <p><b>2. Payment Method</b></p>
                                <p>
                                    Payment shall be made to the CONTRACTOR as
                                    indicated in the Payment Plan, where all
                                    terms are met, the CONTRACT OWNER is
                                    obligated to approve payment ON EasyPay to
                                    the CONTRACTOR.
                                </p>
                            </div>
                            <div style="margin-top: 40px">
                                <p><b>3. Indemnification</b></p>
                                <p>
                                    In consideration for this Agreement, the
                                    CONTRACT OWNER hereby releases any other
                                    claims against the CONTRACTOR in relation to
                                    fees and penalties resulting from the
                                    deficiency or any damages prior to this
                                    Agreement. However, it shall not exculpate
                                    the CONTRACTOR obligations herein or limit
                                    the rights of the CONTRACT OWNER in relation
                                    to this Agreement.
                                </p>
                            </div>
                            <div style="margin-top: 40px">
                                <p><b>4. Acceleration Clause</b></p>
                                <p>
                                    In the occurrence that the CONTRACTOR fails
                                    to fufill the terms of the contract, the
                                    CONTRACT OWNER maintains the right to
                                    terminate the contract. In case the contract
                                    obligations are still not met after this
                                    period, the CONTRACT OWNER also has the
                                    right to demand compensation for its
                                    indirect damages.
                                </p>
                            </div>
                            <div style="margin-top: 40px">
                                <p>
                                    <b
                                        >5. Assignment of Rights and
                                        Obligations</b
                                    >
                                </p>
                                <p>
                                    The CONTRACT OWNER may transfer or assign
                                    this Agreement to a third party provided
                                    that a written notice to the CONTRACTOR is
                                    given. In the event of such assignment, the
                                    assignee may amend the contractual terms
                                    found in this Agreement with written consent
                                    of the CONTRACTOR.
                                </p>
                            </div>
                            <div style="margin-top: 40px">
                                <p><b>6. Amendments</b></p>
                                <p>
                                    No modification of this Agreement shall be
                                    binding or enforceable unless expressly
                                    agreed by both Parties in writing.
                                </p>
                            </div>
                            <div style="margin-top: 40px">
                                <p><b>7. Severability</b></p>
                                <p>
                                    No modification of this Agreement shall be
                                    binding or enforceable unless expressly
                                    agreed by both Parties in writing.
                                </p>
                            </div>
                            <div style="margin-top: 40px">
                                <p>
                                    <b
                                        >8. Applicable Law and Dispute
                                        Resolution</b
                                    >
                                </p>
                                <p>
                                    This Agreement shall be governed by and
                                    construed in accordance with the laws of the
                                    State of __________________. Any dispute
                                    arising out of or in connection with this
                                    Agreement including any question regarding
                                    its existence, validity or termination,
                                    shall be referred to and finally and
                                    exclusively settled by
                                    _______________________________.
                                </p>
                                <div id="textbox mt-5" style="margin-top: 50px">
                                    <div class="alignleft">
                                        <p>
                                            <b>Name & Signature of Creditor</b>
                                        </p>
                                        <p style="margin-top: 70px">
                                            ------------------------------------
                                        </p>
                                    </div>
                                    <div class="alignright">
                                        <p><b>Name & Signature of Debtor</b></p>
                                        <p style="margin-top: 70px">
                                            ------------------------------------
                                        </p>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </tr>
                    </thead>
                </table>
            </div>
            <div>
                <p><b>Payment Schedule/Structure</b></p>
                <table class="table table-bordered mb-5">
                    <thead class="">
                        <tr class="table-dark">
                            <th align="left" scope="col">Payment Method</th>
                            <th align="left" scope="col">Amount</th>
                            <th align="left" scope="col">Scheduled Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th align="left" scope="row">Cash</th>
                            <td align="left">50,000</td>
                            <td align="left">15, May 2023</td>
                        </tr>
                    </tbody>
                </table>
                <div id="textbox mt-5" style="margin-top: 50px">
                    <div class="alignleft">
                        <p><b>Name & Signature of CONTRACTOR</b></p>
                        <p style="margin-top: 70px">
                            ------------------------------------
                        </p>
                    </div>
                    <div class="alignright">
                        <p><b>Name & Signature of CONTRACT OWNER</b></p>
                        <p style="margin-top: 70px">
                            ------------------------------------
                        </p>
                    </div>
                    <div style="clear: both"></div>
                </div>
            </div>
        </div>
    </body>
</html>
