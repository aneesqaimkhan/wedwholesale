@extends('tenant.layouts.admin')

@section('title', 'Outstanding Receivables Report')
@section('page-title', 'Outstanding Receivables Report')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Outstanding Receivables Report</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Track money owed by customers</p>
        </div>
        <a href="{{ route_include_subdirectory('reports.index') }}" class="btn" style="padding: 6px 16px; font-size: 12px;">← Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route_include_subdirectory('reports.outstanding_receivables') }}" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">Sort By</label>
                <select name="sort_by" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="outstanding_amount" {{ $sortBy == 'outstanding_amount' ? 'selected' : '' }}>Outstanding Amount</option>
                    <option value="days_outstanding" {{ $sortBy == 'days_outstanding' ? 'selected' : '' }}>Days Outstanding</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; margin-bottom: 5px;">Ageing Filter</label>
                <select name="ageing_filter" class="form-control" style="font-size: 12px; padding: 8px;">
                    <option value="">All</option>
                    <option value="0-30" {{ $ageingFilter == '0-30' ? 'selected' : '' }}>0-30 Days</option>
                    <option value="31-60" {{ $ageingFilter == '31-60' ? 'selected' : '' }}>31-60 Days</option>
                    <option value="61-90" {{ $ageingFilter == '61-90' ? 'selected' : '' }}>61-90 Days</option>
                    <option value="90+" {{ $ageingFilter == '90+' ? 'selected' : '' }}>90+ Days</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; display: flex; align-items: flex-end;">
                <button type="submit" class="btn" style="width: 100%; padding: 8px;">Generate Report</button>
            </div>
        </div>
    </form>

    <!-- Summary -->
    <div style="background: #6D2D9D; color: white; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
        <div>
            <div style="font-size: 12px; opacity: 0.9;">Total Outstanding Receivables</div>
            <div style="font-size: 24px; font-weight: 600;">{{ number_format($totalOutstanding, 2) }}</div>
        </div>
    </div>

    <!-- Report Data -->
    @if(count($receivablesData) > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer Code</th>
                        <th>Customer Name</th>
                        <th>Invoice No</th>
                        <th>Invoice Date</th>
                        <th>Invoice Amount</th>
                        <th>Amount Received</th>
                        <th>Outstanding Amount</th>
                        <th>Days Outstanding</th>
                        <th>Ageing</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receivablesData as $data)
                    <tr>
                        <td>{{ $data['customer_code'] }}</td>
                        <td>{{ $data['customer_name'] }}</td>
                        <td>{{ $data['invoice_no'] }}</td>
                        <td>{{ $data['invoice_date'] }}</td>
                        <td>{{ number_format($data['invoice_amount'], 2) }}</td>
                        <td>{{ number_format($data['amount_received'], 2) }}</td>
                        <td style="font-weight: 600;">{{ number_format($data['outstanding_amount'], 2) }}</td>
                        <td>{{ $data['days_outstanding'] }}</td>
                        <td>
                            @if($data['ageing'] == '0-30')
                                <span style="color: green;">{{ $data['ageing'] }}</span>
                            @elseif($data['ageing'] == '31-60')
                                <span style="color: orange;">{{ $data['ageing'] }}</span>
                            @elseif($data['ageing'] == '61-90')
                                <span style="color: #ff6b00;">{{ $data['ageing'] }}</span>
                            @else
                                <span style="color: red;">{{ $data['ageing'] }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 15px; text-align: right;">
            <button onclick="window.print()" class="btn">Print Report</button>
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #666;">
            <p>No outstanding receivables found.</p>
        </div>
    @endif
</div>
@endsection

